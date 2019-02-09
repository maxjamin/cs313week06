<?php
	//Starting session
	session_start();
	//$_SESSION["sessionUserName"] = "";
	//$_SESSION["table"] = 'true';
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

	$nameError = $passError = "";
	$name = $password = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(empty($_POST["userNameEntered"])) {
			$nameError = "Please enter a username";
		}else {
			$name = $_POST["userNameEntered"];
			//echo "Test name" . $name;
		}
		
		
		if(empty($_POST["passwordEntered"])) {
			$passError = "Please enter a username";
		}else {
			$password = $_POST["passwordEntered"];
			//echo "Test name" . $password;
		}
		
		//echo 'Test<br/>';
		$stmt = $db->prepare('SELECT * FROM Customer');
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$found = 0;
		foreach($rows as $table){
    		if($name === $table['username'] && $password === $table['login'])
    		{
    			//echo 'Test 777 ' . $table['email'] . " " . $table['user_id'] ;
    			$_SESSION["sessionUserName"] = $table['username'];
    			$_SESSION["sessionUserEmail"]= $table['email'];
    			$_SESSION["sessionUserId"]   = $table['user_id'];
    			$_SESSION["table"] = 'false';
    			$found = 1;
    		}
		}
		if($found === 0 && $_POST["userNameEntered"] && $_POST["passwordEntered"])
		{
			$nameError = "Please enter a real username";
			$passError = "Please enter a real password";
		}

	}

?>
	<h1>05 Prove</h1>
	<br>

	<div class="navbar">
		<a href="main.php">Login</a>
		  		<?php
		if($_SESSION["sessionUserName"]) {?>
  		<a href="menu.php">Gallery</a>
  		<a href="cart.php">Cart</a>
  		<?php 
 		}?>
	</div>
	<br>

	<?php
		if($_SESSION["sessionUserName"]) {
			echo "User: " . $_SESSION["sessionUserName"] . '<br>';
			echo "User Email: " . $_SESSION["sessionUserEmail"] . '<br><br>';
		}

	?>

	<p>Default User: maxer, Password:password </p>

	<?php if($_SESSION["table"] !== 'false') { ?>

		<form id = "table" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			UserName:<input type="text" placeholder="Enter Username" name="userNameEntered">
			<span class="error"><?php echo $nameError;?></span><br>
			Password:<input type="password" placeholder="Enter Password" name="passwordEntered">
			<span class="error"><?php echo $passError;?></span><br>
			<input type="submit" name="entered" value="submit">
		
			<br><br>
			<div class="container" style="background-color:#f1f1f1">
	    		<span class="passwordF">Forgot <a href="#">password?</a></span><br><br>
	    		<span class="addUser"><a href="addUser.php"</a>Add User</span>
	  		</div>
	  	</form>
		
	<?php } 

	else {
	?>
	<span class="addUser"><a href="logout.php"</a>Log Out</span>
	<?php } ?>



</body>
</html> 