<?php
	$servername = "localhost";
	$database = "2809983979_gru";
	$username = "2809983979";
	$password = "bZvZzW7YfpLrfzhy";

	try {
		$db = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		echo "Connection failed! ".$e->getMessage();
	}
?>