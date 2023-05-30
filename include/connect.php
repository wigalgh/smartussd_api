<?php
	$servername = "localhost";
	$user = "your_db_username";
	$password = "your_db_password";

	try {
	    $conn = new PDO("mysql:host=$servername;dbname=bakeside", $user, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    //echo "all good";
	    }
	catch(PDOException $e)
	    {
	    echo "Connection failed: " . $e->getMessage();
	    }
?>
