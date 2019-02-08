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

  	
  	$user = $_SESSION["sessionUserName"];

	//find the user in the DB
	$stmt = $db->prepare('SELECT username, email  FROM Customer WHERE username=:id');
	$stmt->bindValue(':id', $user, PDO::PARAM_STR);
	$stmt->execute();
	$note_rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo . 'Answer ' . $note_rows[0]['username'] . $note_rows[0]['email'];

	



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





</body>
</html> 