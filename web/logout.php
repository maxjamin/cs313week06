<?php 

	//Starting session
	session_start();

	//destroy the sessions
  	session_unset();
  	session_destroy();


  	$new_page = "main.php";
	header("Location: $new_page");
	die();


?>