<?php 

	//Starting session
	session_start();

	$adress = htmlspecialchars($_POST['addressEntered']);
	$zip = htmlspecialchars($_POST['zipEntered']);
	$state = htmlspecialchars($_POST['stateEntered']);
	$sessionUser = $_SESSION["sessionUserName"];

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

	echo $sessionUser . ' ' . $adress . $zip . $state;


	$stmt = $db->prepare('SELECT * FROM Customer WHERE userName=$sessionUser');
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	echo $rows;


?>
