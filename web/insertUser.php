<?php 

	$customerName= htmlspecialchars($_POST['name']);
	$customerEmail = htmlspecialchars($_POST['email']);
	$customerPass = htmlspecialchars($_POST['password']);

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

	$stmt = $db->prepare('INSERT INTO Customer(userName, email, login) VALUES (:customerName, :customerEmail, :customerPass);');
	$stmt->bindValue(':customerName', $customerName, PDO::PARAM_STR);
	$stmt->bindValue(':customerEmail', $customerEmail, PDO::PARAM_STR);
	$stmt->bindValue(':customerPass', $customerPass, PDO::PARAM_STR);
	$stmt->execute();

	$new_page = "main.php";
	header("Location: $new_page");
	die();
?>
