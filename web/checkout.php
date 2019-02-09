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

?>
	<h1>Checkout</h1>
	<br>

	<div class="navbar">
		<a href="main.php">Login</a>
  		<a href="menu.php">Gallery</a>
  		<a href="cart.php">Cart</a>
	</div>
	<br>

	<form id = "table" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			UserName:<input type="text" placeholder="Enter address" name="userNameEntered">
			<span class="error"><?php echo $nameError;?></span><br>
			Password:<input type="password" placeholder="State" name="passwordEntered">
			<span class="error"><?php echo $passError;?></span><br>
			<input type="submit" name="entered" value="submit">
		

	</form>




</body>
</html> 