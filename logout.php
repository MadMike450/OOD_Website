<?php 
session_start();
include 'includes/head.php';
include 'includes/header.php';

session_destroy();
?>

<!DOCTYPE html>
<html lang="en"> 
 
<div id="wrap">
	<div id="main">
		<div class="container">
			
			<h3>Dashboard</h3>

			<div class="row">
				<div class="col-md-12" style="margin-bottom:10px">
					<p>You have been logged out.</p>
				</div>
				<div class="col-md-12" style="margin-bottom:10px">
					<a href="loginPage.php" class="btn btn-primary btn-lg btn-block btn-huge">Log In Again</a>
					<a href="index.php?page=1" class="btn btn-primary btn-lg btn-block btn-huge">Home Page</a>
				</div>
				
			</div>	
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>