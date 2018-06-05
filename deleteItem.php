<?php 
session_start();
include 'includes/header.php';
include 'includes/functions.php';

//Get the current page name in the URL
$fromPage = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

//Get the original page number we came from on deleteForm page.
$fromPageNum = $_GET['fromPageNum'];

//This is the page number a back or cancel button will return you to.
$returnPageNum = isset($_GET['fromPageNum']) ? $_GET['fromPageNum'] : 'X';

//This is the page name a back or cancel button will return you to.
$returnPage = isset($_GET['fromPage']) ? $_GET['fromPage'] : 'X';
?>

<!DOCTYPE html>
<html lang="en">

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
				<a class="btn btn-default pull-left navbar-btn" href="./dashboard.php">Dashboard</a>
				<a class="btn btn-default pull-left navbar-btn" href="./deleteForm.php?page=<?php echo $returnPageNum; ?>">Back</a>
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
						?>
							<div class="row">
								<center>
									<div class="box-icon">
										<img class="img-responsive" alt="Brand" src="<?php echo $row['imagePath']; ?>">
									</div>
								</center>
							</div>
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3">
									<div class="box">
										<div class="info">
											<h4 class="text-center"><?php echo $row['title']; ?></h4>
											<h6 class="text-center">$<?php echo $row['price']; ?></h6>
											<h6 class="text-center"><?php echo $row['shortDesc']; ?></h6>
											<p><?php echo $row['longDesc']; ?></p>
											<a class="btn btn-small btn-danger" href="deleteConfirm.php?productID=<?php echo $productID; ?>&fromPage=<?php echo $fromPage; ?>&fromPageNum=<?php echo $fromPageNum; ?>">Delete</a>
										</div>
									</div>
								</div>
							</div>
						<?php
						}	
					} 
					else { 
					?>
						<p class="text-center alert alert-warning">
							<center>0 results</center>
						</p>
					<?php
					}							
					mysqli_close($conn); // close database connection
					?>
					
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'includes/footer.php';?>