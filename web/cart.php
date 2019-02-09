<?php	
	//Starting session
	session_start();
	$_SESSION["addedToCart"] = 0;
?>
<!DOCTYPE html>
<html>
<head>
<title>Gallery</title>
	<meta charset="UTF-8">
	<link rel = "stylesheet" type = "text/css" href = "myStyle.css" />
</head>
<body>

<?php 

try
{
	$dbUrl = getenv('DATABASE_URL');
	$dbOpts = parse_url($dbUrl);

	$dbHost = $dbOpts["host"];
	$dbPort = $dbOpts["port"];
	$dbUser = $dbOpts["user"];
	$dbPassword = $dbOpts["pass"];
	$dbName = ltrim($dbOpts["path"],'/');

 	$db = new PDO("pgsql:host=$dbHost;port=$dbPort;dbname=$dbName", $dbUser, $dbPassword);
  	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch (PDOException $ex)
{
  echo 'Error!: ' . $ex->getMessage();
  die();
}

?>
	<h1>Cart</h1>
	<br>

	<div class="navbar">
		<a href="main.php">Login</a>
  		<a href="menu.php">Gallery</a>
  		<a href="cart.php">Cart</a>
  		<?php
		if($_SESSION["addedToCart"] == 1) {?>
 			<a href="checkout.php">Checkout</a>
 		<?php 
 		}?>
	</div>
	<br>

	<?php
		if($_SESSION["sessionUserName"]) {
			echo "User: " . $_SESSION["sessionUserName"] . '<br>';
			echo "User Email: " . $_SESSION["sessionUserEmail"] . '<br><br>';
		}

		$stmt = $db->prepare('SELECT * FROM Artwork');
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


		//remove From cart 
		$ouputAmount = $_POST["output"] . 'amount';
		$_SESSION[$ouputAmount] = "";
		$_SESSION[$_POST['output']] = "";

		//print sessions vars 
		/*echo '<pre>';
			var_dump($_SESSION);
		echo '</pre>';	*/	

	?>	


		<table>
  		<tr>
    		<td>Name:</td>
   			<td>Description</td>
   			<td>Image:</td>
   			<td>Price:</td>
   			<td>Quantity</td>
   			<td>Add</td>
   		</tr>
  		<?php

		foreach($rows as $table){
			$image = "artWorkImages/" . $table['linktoart'];
			$id = $table['artwork_id'];
			$names = $table['name'];
			
			if( $_SESSION[$table['name']] == $table['artwork_id'])
			{
				$_SESSION["addedToCart"] = 1;

				$productName = $table['name'];
				echo '<tr><td>' . $table['name'] .
				"</td><td>" . $table['description'] .
				"</td><td>" . "<img src=$image width='150' height='150'>" .
				"</td><td>" . $table['price'] .
				"</td><td>" . $_SESSION[$names . 'amount'] .
				"</td><td>";
				?>
				<form id = "table" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input value="<?php echo $id;?>" type="hidden" name="search">
					<input value="<?php echo $names?>" type="hidden" name="output">
					<input type="submit" name="AddToCart" value="Remove from Cart">
				</form>
			
				<?php 
				echo '</td></tr>';

			} 

				
		}
		
		?>
		</table>


</body>
</html> 