<?php 
session_start();
include 'includes/header.php';
include 'functions/general.php';
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
			<a class="btn btn-default pull-left navbar-btn" href="./deleteForm.php">Back</a>
			<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
		</div>
	</nav>
	<div id="main">
		<div class="container">
						
			<h2>Remove Item</h2>
					
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12" style="margin-bottom:10px">	
					<?php

					// Connect to the database
					$conn = db_connector();

					$productID = $_GET['productID'];
					
					$sql = 'SELECT  *
							FROM	products	
							WHERE	productID = ' . $_GET['productID'] . '  ';		
							
					$result = $conn->query($sql);	

					//This displays the title, price, and description
					if ($result->num_rows > 0) {
						// output data of each row
						while($row = $result->fetch_assoc()) {

							echo"<div class='row'><center>";
							echo"<div class='box-icon'>";
							echo"<img class='img-responsive' alt='Brand' src='" . $row["imagePath"] . "'> ";
							echo"</center></div>";
							echo"</div>";
							echo"<div class='row'>";
							echo"<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3'>";
							echo"<div class='box'>";
							echo"<div class='info'>";
							echo"<h4 class='text-center'>" . $row["title"] . "</h4>";
							echo"<h6 class='text-center'>$" . $row["price"] . "</h6>";
							echo"<h6 class='text-center'>" . $row["shortDesc"] . "</h6>";
							echo"<p>" . $row["longDesc"] . "</p>";
							echo"<a class='btn btn-small btn-danger' onclick='return confirm('Remove this item?')' href='deleteConfirm.php?del=$productID'><span class='glyphicon glyphicon-remove' aria-hidden='true'></span></a>";
							echo"</div></div></div>";
						}	
					} 
					else {
						echo "<p class='text-center alert alert-warning'><center>0 results</center></p>";
					}							
					mysqli_close($conn); // close database connection
					?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'includes/footer.php';?>