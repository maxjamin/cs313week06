<?php 

	//Starting session
	session_start();

	$adress = htmlspecialchars($_POST['addressEntered']);
	$zip = htmlspecialchars($_POST['zipEntered']);
	$state = htmlspecialchars($_POST['stateEntered']);

	$sessionUser = $_SESSION["sessionUserName"];
	$addressId = $address . " " . $zip . " " . $state;

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

	//check to see if the user_id matches the session id of the user
	$stmt = $db->prepare('SELECT * FROM Customer WHERE userName=:sessionUser');
	$stmt->bindValue(':sessionUser', $sessionUser, PDO::PARAM_STR);
	$stmt->execute();
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


	//Add the user_id into the Order table
	if($rows[0]["username"])
	{
		echo $rows[0]["username"] ." " . $rows[0]["user_id"];
		$userId = $rows[0]["user_id"];

		$stmt = $db->prepare('INSERT INTO Orders(address, user_id) VALUES (:address :userId);');
		$stmt->bindValue(':userId', $userId, PDO::PARAM_STR);
		$stmt->bindValue(':address', $addressId, PDO::PARAM_STR);
		$stmt->execute();


	}
	else{
		echo "Error: User not found<br>";
	}

	


?>
