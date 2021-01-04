	<?php
	$servername = "localhost";
	$user = "your_dbusername";
	$password = "your_dbpassword";

	try {
	    $conn = new PDO("mysql:host=$servername;dbname=bakeside", $user, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    }

	catch(PDOException $e)
	    {
	    echo "Connection failed: " . $e->getMessage();
	    }
?>
