<?php 

	$dsn = "mysql:host=localhost;dbname=blogdb";
	$username = "root";
	$dbpassword = "";

	try {
		$con = new PDO($dsn, $username, $dbpassword);
		$con->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e) {
		echo "Error: ". $e->getMessage();
	}

?>