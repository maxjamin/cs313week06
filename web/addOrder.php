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


	  		echo'ADDRESS IS: ' . $adress;

		//check to see if the user_id matches the session id of the user
		$stmt = $db->prepare('SELECT * FROM Customer WHERE userName=:sessionUser');
		$stmt->bindValue(':sessionUser', $sessionUser, PDO::PARAM_STR);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);



		//Add the user_id into the Order table
		if($rows[0]["username"])
		{
			//echo $rows[0]["username"] ." " . $rows[0]["user_id"];
			$userId = $rows[0]["user_id"];

			$stt = $db->prepare('INSERT INTO Orders(address, user_id, zip, state) VALUES (:addressId, :userId, :zipId, :stateId);');
			$stt->bindValue(':addressId', $adress, PDO::PARAM_STR);
			$stt->bindValue(':zipId', $zip, PDO::PARAM_STR);
			$stt->bindValue(':stateId', $state, PDO::PARAM_STR);
			$stt->bindValue(':userId', $userId, PDO::PARAM_INT);
			$stt->execute();

			$newId = $pdo->lastInsertId('Orders_order_id_seq');


			//Add OrderItems items to table
			$stmt = $db->prepare('SELECT * FROM Artwork');
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

			foreach($rows as $table){
					
				if($_SESSION[$table['name']] == $table['artwork_id']) {

					$ouputAmount = $table["name"] . 'amount';
					echo $table['name'] . " " . $_SESSION[$ouputAmount];
					


				}

			}



		}
		else{
			echo "Error: User not found<br>";
		}


	}
	catch (PDOException $ex)
	{
	  echo 'Error!: ' . $ex->getMessage();
	  die();
	}


	


?>
