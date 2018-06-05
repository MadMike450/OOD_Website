<!DOCTYPE html>
<html lang="en">

<?php 
session_start();
include 'includes/header.php';
?>

<div id="wrap">

	<!-- NEW header logo, header buttons, and session -->
	<nav class="navbar navbar-default">
		<div class="container">
			
			<div class="navbar-header">
				<a class="navbar-brand" href="./index.php?page=1">
					<img class="img-responsive" alt="Business Logo" src="./images/logo.jpg" width="100px">
				</a>
			</div>
			
			<?php if (isset($_SESSION['username'])){ ?>
				<p class='navbar-text'>Logged in as: <?php echo strtoupper($_SESSION['username']); ?></p>
				
				<a class="btn btn-default pull-left navbar-btn" href="./index.php?page=1">Home</a>
				<!-- <a class="btn btn-default pull-left navbar-btn" href="./dashboard.php">Dashboard</a> -->
				<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
			<?php } else{ ?>
				<a class="btn btn-default pull-left navbar-btn" href="./index.php?page=1">Home</a>
				<a class="btn btn-default pull-right navbar-btn" href="./loginPage.php">Log In</a>
			
			<?php	
				die ("You must be logged in!");
			}
			?>
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
					<a href="editItems.php?page=1" class="btn btn-primary btn-lg btn-block btn-huge">Edit Item</a>
				</div>
				<div class="col-md-4 col-md-offset-4" style="margin-bottom:10px">
					<a href="QRDownload.php?page=1" class="btn btn-primary btn-lg btn-block btn-huge">Download QR Codes</a>
				</div>
			</div>	
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>