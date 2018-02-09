<?php 
session_start();
include 'includes/header.php';

$productID = $_GET['del'];
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
				echo "<p class='navbar-text'>Logged in as: " . strtoupper($_SESSION['username']) . "</p>";
			else{
				echo "<a class='btn btn-default pull-left navbar-btn' href='./index.php'>Home</a>";
				echo "<a class='btn btn-default pull-right navbar-btn' href='./loginPage.php'>Log In</a>";
				die ("You must be logged in!");
			}
			?>
			<!-- <a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a> -->
		</div>
	</nav>
	
	
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 col-sm-offset-3 col-md-offset-3" style="margin-bottom:10px">
					<ul><h4 class='text-center alert alert-warning'>Are you sure you want to delete this item?</h4></ul>
					<?php echo "<ul><a href='deleteRecord.php?del=" . $productID . "'class='btn btn-danger btn-lg btn-huge btn-block'>Delete</a></ul>"; ?>
					<ul><a href="deleteForm.php" class="btn btn-default btn-lg btn-huge btn-block">Cancel</a></ul>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>
