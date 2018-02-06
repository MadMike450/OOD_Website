<?php 
include 'includes/head.php';
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<div id="wrap">
	<!-- header logo and buttons -->
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php?page=1">
					<img class="img-responsive"  alt="Brand" src="./images/logo.jpg" width="100px" >
				</a>
			</div>
			<a class="btn btn-default pull-right navbar-btn" href="./index.php?page=1">Home</a>
		</div>
	</nav>
	<div id="main">
		<div class="container">
						
			<h2></h2>
					
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12" style="margin-bottom:10px">	
					<?php
					//-------------connect to the database-------------
					$servername = 'mysql.objectsofdesirefindlay.com';
					$user       = 'jasrhu2';
					$password   = 'QRcodes21';
					$dbname     = 'qrusers';

					$conn = new mysqli($servername, $user, $password, $dbname) or die("Unable to connect to the database");

					$productID = $_GET['productID'];
					
					$sql = 'SELECT  *
							FROM	products	
							WHERE	productID = ' . $_GET['productID'] . '  ';		
							
					$result = $conn->query($sql);	

					//This displays the title, price, and description
					if ($result->num_rows > 0) {
						// output data of each row
						while($row = $result->fetch_assoc()) {
							
							// related items will be related.php variable is rel
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
							echo"<a class='btn btn-small btn-success' href=/related.php?rel='"  . urlencode($row["itemTag"]) . "'&productID=" . urlencode($row['productID']) . "> Related Items  <span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>";
							echo"</div></div></div>";
						}	
					} 
					else {
						echo "0 results";
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