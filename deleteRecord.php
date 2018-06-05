<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php 
include 'includes/header.php';
include 'includes/functions.php';
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
				<a class="btn btn-default pull-left navbar-btn" href="./dashboard.php">Dashboard</a>
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
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3">
					<?php

					// Connect to the database
					$conn = db_connector();
					
					if (isset($_GET['productID'])) {
						$id       = $_GET['productID'];
						$imageSql = "SELECT imagePath, qrCodePath
									 FROM 	products
									 WHERE 	productID = " . $id;
						$sql      = "DELETE FROM products
									 WHERE 	productID = " . $id;
					}

					if ($results = mysqli_query($conn, $imageSql)){
						$record = mysqli_fetch_assoc($results);
						
						// Remove product record from database
						if (mysqli_query($conn, $sql)){
							echo "<p class='text-center alert alert-success'><strong>Product Record Deleted Successfully.</strong></p>";
							
							// Remove QR code image from server
							unlink($record['qrCodePath']);

							// Verify this item isn't using the default "no image" picture. If it is then we do not need to remove a picture
							//    for this item because one was not uploaded when the item was created.
							if ($record['imagePath'] != "uploads/noimage.png" && $record['imagePath']) {
								// *** Check if image exist first to prevent an error ***
									// Remove product image from server
									unlink($record['imagePath']);
									echo "<p class='text-center alert alert-success'>Product image deleted successfully.</p>";
							}
							else {
								echo "<p class='text-center alert alert-success'><strong>Programmer Alert 0:</strong> No image was uploaded for this item, therefore no image was removed.</p>";
							}
						}
						else {
							echo "<p class='text-center alert alert-danger'><strong>Programmer Alert 1:</strong> Product SQL query failed.</p>";
							echo "<p class='text-center alert alert-danger'><strong>ALERT 2:</strong> This item was NOT removed.</p>";
						}
					}
					else {
						echo "<p class='text-center alert alert-danger'><strong>Programmer Alert 2:</strong> Image & QR Code path query failed.</p>";
						echo "<p class='text-center alert alert-danger'><strong>ALERT 1:</strong> This item was NOT removed.</p>";
					}
					
					
					
					if ($results) {
						mysqli_free_result($results);
						echo "<p>Results Freed</p>"; //testing only
					}
					if ($conn) {
						mysqli_close($conn);
						echo "<p>Connection Closed</p>"; //testing only
					}
					?>
				</div>
			</div>	
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3">
					<a class="btn btn-primary pull-left navbar-btn" href="deleteForm.php">Remove Another Item</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'includes/footer.php'; ?>