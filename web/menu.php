<?php	
	//Starting session
	session_start();
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

		//grab from session to display user
		if($_SESSION["sessionUserName"]) {
			echo "User: " . $_SESSION["sessionUserName"] . '<br>';
			echo "User Email: " . $_SESSION["sessionUserEmail"] . '<br><br>';
		}

		//grab from pssql to table
		$stmt = $db->prepare('SELECT * FROM Artwork');
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


		//Add To Cart/ add to session variables for cart page
		$amount = $_POST["output"] . 'amount';
		

		if(!filter_var($_POST["search"], FILTER_VALIDATE_INT) === false) {

			//echo "TEST<br>";
			if($_SESSION[$_POST["output"]] !== $_POST["search"])
			{
				//echo "TEST01";
				$_SESSION[$amount] = 1;
				$_SESSION[$_POST["output"]] = $_POST["search"];
			}
			else
			{
				//echo "TEST02";
				$tempVar = $_SESSION[$amount];
				$tempVar = $tempVar +1;
				$_SESSION[$amount] = $tempVar;
			}

		}

		/*echo '<pre>';
		var_dump($_SESSION);
		echo '</pre>';*/


?>
	<h1>Gallery</h1>
	<br>

	<div class="navbar">
		<a href="main.php">Login</a>
  		<a href="cart.php">Cart</a>
	</div>
	<br>



	<table>
  		<tr>
    		<td>Name:</td>
   			<td>Description</td>
   			<td>Image:</td>
   			<td>Price:</td>
   			<td>Add</td>
   		</tr>
  		<?php
		foreach($rows as $table){
			$image = "artWorkImages/" . $table['linktoart'];
			$id = $table['artwork_id'];
			$names = $table['name'];
			//"<img src='artWorkImages/weather.jpeg' >"

			echo '<tr><td>' .  $table['name'] .
				"</td><td>" . $table['description'] .
				"</td><td>" . "<img src=$image width='150' height='150'>" .
				"</td><td>" . $table['price'] .
				"</td><td>";
				?>
				<form id = "table" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input value="<?php echo $id;?>" type="hidden" name="search">
					<input value="<?php echo $names?>" type="hidden" name="output">
					<input type="submit" name="AddToCart" value="Add to Cart">
				</form>

				<?php 
				echo '</td></tr>';
		} ?>

	</table>



</body>
</html> 