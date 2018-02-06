<?php
session_start();
//error_reporting(0);
require 'functions/general.php';
require 'functions/users.php';

$errors = array();
if (empty($_POST) == false){
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if (empty($username) === true || empty($password) === true){
		$errors[] = 'You need to enter a username and password';
	} 
	
	mysql_connect('mysql.objectsofdesirefindlay.com', 'jasrhu2', 'QRcodes21') or die('could not connect');
    mysql_select_db('qrusers') or die('could not find db');
	$username = sanitize($username);
	$query = mysql_query("SELECT * FROM adminusers WHERE username = '$username'");
	$numrows = mysql_num_rows($query);
	
	
	if (userExists($username) == false){
		//$errors[] = 'We can\'t find that username.  Please retype.';
			
			header('refresh: 0; URL = loginPage.php');
			
			//script that displays error message with wrong credentials
			echo '<script language="javascript">';
			echo 'alert("That username does not exist")';
			echo '</script>';
			
	}
	else{
		//log the user in
		$login = login($username, $password);
		if ($login == false){
			$errors[] = 'That username/password combination is incorrect.';
			//redirects the user back to login page
			header('refresh: 0; URL = loginPage.php');
			
			//script that displays error message with wrong credentials
			echo '<script language="javascript">';
			echo 'alert("That username/password combination is incorrect.")';
			echo '</script>';
			
			
		}
		else{
			//set the user session
			$_SESSION['username'] = $username;
			//redirect to dashboard
			header('Location: ./dashboard.php');
		}
	}
	//print_r($errors);
}
?>
<!DOCTYPE html>
<html lang="en">
	<div>
		<a class="btn btn-default pull-left navbar-btn" href="index.php">Return to login</a>
	</div>