<!DOCTYPE html>
<html lang="en">

<!-- --------------------------------------------------------------------------------------- -->

<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';

$errorMsg = "";

// Connect to the database
$conn = db_connector();

if (empty($_POST) === true){
	// The user data was NOT passed to the $_POST variable
	$errorMsg = "The webpage failed to pass your login credentials. Please try again.";
}
else{ // The user data was successfully passed to the $_POST variable.
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	
	if (empty($username) === true || empty($password) === true){
		// Both fields were NOT filled in.
		
		/*
		// Redirect user back to login page
		header('refresh: 0; URL = loginPage.php');
		
		// Script that displays error message to the user.
		echo '<script language="javascript">';
		echo 'alert("ERROR 888: You need to enter a username and password")';
		echo '</script>';
		*/
		
		$errorMsg = "Username or password field was not filled in.";
	}
	else{ // Both the username and password fields were filled in properly.		
		// Check for the username in the adminusers database.
		$username = mysqli_real_escape_string($conn, $username);
		$sql = "SELECT *
				FROM   adminusers
				WHERE  username = '$username'";
		$results = mysqli_query($conn, $sql);
		
		
		if (mysqli_num_rows($results) === 0){
			// Username entered is NOT valid
			
			mysqli_free_result($results);
			
			/*
			// Redirect user back to login page
			header('refresh: 0; URL = loginPage.php');
			
			// Script that displays error message with wrong credentials
			echo '<script language="javascript">';
			echo 'alert("ERROR 888: That username does not exist")';
			echo '</script>';
			*/
			
			$errorMsg = "The username you have entered is not valid.";
		}
		else{ // Username entered is valid
			mysqli_free_result($results);
			
			// Verify the entered credentials
			$password = md5($password);
			$sql = "SELECT user_id
					FROM   adminusers
					WHERE  username = '$username' 
					AND    password = '$password'";
			$results = mysqli_query($conn, $sql);
			
			
			if (mysqli_num_rows($results) === 0){
				// Username and password entered are NOT valid.
				
				mysqli_free_result($results);
				
				/*
				// Redirect user back to login page
				header('refresh: 0; URL = loginPage.php');
				
				// Script that displays error message with wrong credentials
				echo '<script language="javascript">';
				echo 'alert("ERROR 888: That username/password combination is incorrect.")';
				echo '</script>';
				*/
				
				$errorMsg = "The username and password entered are incorrect.";
			}
			else{ // Username and password entered are valid
				
				// Set the user session
				$_SESSION['username'] = $username;
				
				mysqli_free_result($results);
				
				// Redirect user to the dashboard
				header('Location: ./dashboard.php');
			}
		}
	}
}
mysqli_close($conn);

?>

<!-- --------------------------------------------------------------------------------------- -->

<div id="wrap">
	<div class="container">
		<div class="text-center col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-4" style="margin-bottom:10px">
			<h3 class="text-center alert alert-danger">Login Unsuccessful</h3>
			<p class="alert alert-danger"><strong>Error: </strong><?php echo $errorMsg; ?></p>
			<p><a href="loginPage.php" class="btn btn-primary btn-lg btn-huge btn-block">Try Login Again</a></p>
			<p><a href="index.php?page=1" class="btn btn-primary btn-lg btn-huge btn-block">Return To Home Page</a></p>
		</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>
