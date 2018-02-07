<?php

// Function to connect to my database.
function db_connector(){
	$servername = 'localhost';
	$user       = 'root';
	$password   = 'abc123';
	$dbname     = 'test_db_ood_website';
	
	$conn = new mysqli($servername, $user, $password, $dbname) or die("ERROR in general.php db_connector function.  Unable to connect to the database");
	return $conn;
}
?>