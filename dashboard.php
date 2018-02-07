<?php 
session_start();
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<div id="wrap">

	<!-- header logo and buttons -->
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="#">
					<img class="img-responsive" alt="Brand" src="./images/logo.jpg" width="100px">
				</a>
			</div>
			<?php
			if ($_SESSION['username'])
				echo "<p class='navbar-text'>Welcome, " .$_SESSION['username']. "!</p>";
			else{
				echo "<a class='btn btn-default pull-left navbar-btn' href='./index.php'>Home</a>";
				echo "<a class='btn btn-default pull-right navbar-btn' href='./loginPage.php'>Log In</a>";
				die ("You must be logged in!");
			}
			?>
			<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
		</div>
	</nav>
	
	<!-- Dashboard Buttons -->
	<div id="main">
		<div class="container">

			<center><h4>Dashboard</h4></center>

			<div class="row">
				<div class="col-md-4 col-md-offset-4" style="margin-bottom:10px">
					<a href="addForm.php" class="btn btn-primary btn-lg btn-block btn-huge">Add Item</a>
				</div>
				<div class="col-md-4 col-md-offset-4" style="margin-bottom:10px">
					<a href="deleteForm.php?page=1" class="btn btn-primary btn-lg btn-block btn-huge">Remove Item</a>
				</div>
				<div class="col-md-4 col-md-offset-4" style="margin-bottom:10px">
					<a href="update.php?page=1" class="btn btn-primary btn-lg btn-block btn-huge">Edit Item</a>
				</div>
				<div class="col-md-4 col-md-offset-4" style="margin-bottom:10px">
					<a href="QRDownload.php?page=1" class="btn btn-primary btn-lg btn-block btn-huge">Download QR Codes</a>
				</div>
			</div>	
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>