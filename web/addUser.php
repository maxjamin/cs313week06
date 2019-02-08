<?php
	//Starting session
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>05 Prove</title>
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
	<h1>05 Prove</h1>
	<br>

	<div class="navbar">
  		<a href="main.php">Login</a>
	</div>
	<br>

	<?php
		if($_SESSION["sessionUserName"]) {
			echo "User: " . $_SESSION["sessionUserName"] . '<br>';
			echo "User Email: " . $_SESSION["sessionUserEmail"] . '<br><br>';
		}
	?>

	<form method="post" action="insertUser.php">
		Name: <input type="text" name="name"><br>
		Email: <input type="text" name="email"><br>
		Password: <input type="text" name="password"><br>
		<input type="submit">
	</form>



</body>
</html> 