<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<?php 
include 'includes/header.php';
include 'functions/general.php';

// Connect to the database
$conn = db_connector();
?>

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
				echo "<p class='navbar-text'>Welcome, " . $_SESSION['username']. "!</p>";
			else{
				echo "<a class='btn btn-default pull-left navbar-btn' href='./index.php'>Home</a>";
				echo "<a class='btn btn-default pull-right navbar-btn' href='./loginPage.php'>Log In</a>";
				die ("You must be logged in!");
			}
			?>
			<a class="btn btn-default pull-left navbar-btn" href="./dashboard.php">Dashboard</a>
			<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
		</div>
	</nav>
	
	<div id="main">
		<div class="container">
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3">
					<?php
					// PROCESS FLOW:
					// - Populate variables with values from the form ("n" at the beginning of variables means "new" value)
					// - Get the current (old) image path/name
					// - Remove the current (old) image from the server
					// - 
					// - 
					// - 
					// - 
					
					
					// This variable holds the default image path string. This image is only used if no image was selected
					//    when the item was added.
					$defaultImagePath = "uploads/noimage.png";
					
					
					//-------------------- Populate variables from edit form --------------------
					$nProductID  = $_POST['productID'];
					$nTitle      = $_POST['title'];
					$nPrice      = $_POST['price'];
					$nShortDesc  = $_POST['shortDesc'];
					$nLongDesc   = $_POST['longDesc'];
					$nImageName  = $_FILES["fileToUpload"]["name"];
					$nQRCodePath = $_POST['qrtitle'];
					$nItemTag    = $_POST['itemTag'];
					
					
					//-------------------- Retrieve the old image path. --------------------
					// Retrieve image path in case the same image is used or to delete the
					// image if a new one is uploaded.
					$imgQuery = "SELECT imagePath
								 FROM	products
								 WHERE	title = '" . $nTitle . "'";
					if ($imgResults = mysqli_query($conn, $imgQuery)){
						$imagePath  = mysqli_fetch_assoc($imgResults);
						$imagePath  = $imagePath['imagePath'];
					}
					else {
						// ImagePath query failed.
						echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>SQL query for the imagePath failed.</p>";
					}
					
					
					//-------------------- Delete the old image from the server if a new image was selected --------------------
					// Check if a new image was selected.
					if ($nImageName){
						// New image selected, delete the old one first.
						if ($imagePath && $imagePath !== $defaultImagePath){
							// If the default image was used for this item then there is no
							// need to remove an image from server.
							unlink($imagePath);
						}
						$newImage  = 1;
						$imagePath = "uploads/" . $nImageName;
					}
					else {
						// Using the same image.
						$newImage = 0;
					}
					
					
					//----------------------- Upload the new image to the uploads folder ------------------------------------------
					$uploadOk = 1;
					// Do not need to perform upload if original image is used.
					if ($newImage){
						$target_dir = "uploads/";
						$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
						$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
						
						// Check if image file is actually an image or fake image.
						if(isset($_POST["submit"])){
							$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
							if($check !== false){
								echo "<p>File is an image - " . $check["mime"] . ".</p>";
								$uploadOk = 1;
							} else {
								echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>File is not an image.</p>";
								$uploadOk = 0;
							}
						}
						
						// Check if file already exists.
						if (file_exists($target_file)){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>File already exists.</p>";
							$uploadOk = 0;
						}
						
						// Check file size - unit is in bytes (5MB).
						if ($_FILES["fileToUpload"]["size"] > 5000000){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>Your file is above the 5MB limit.</p>";
							$uploadOk = 0;
						}
						
						// Limit the type of file formats.
						if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>Only JPG, JPEG, and PNG files are allowed.</p>";
							$uploadOk = 0;
						}
						
						// Check if $uploadOk has been set to 0 by an error.
						if ($uploadOk == 0){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>Your file was not uploaded.</p>";
						} 
						// If everything is ok, try to upload file.
						else {
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
								// No need to tell the user this succeeded, only if it failed.
								//echo "<p>The file " . basename( $_FILES["fileToUpload"]["name"]) . " has been uploaded.</p>";
							} 
							else {
								echo "<p class='text-center alert alert-danger'><strong>Programmer Error 8: </strong>There was an error uploading your file.</p>";
								$uploadOk = 0;
							}
						}
					}
					
					
					if ($uploadOk == 1){
						//------------------------ Update the record with the new product info -------------------------------
						$sqlUpdateRow = "UPDATE products SET title='$nTitle', price='$nPrice', shortDesc='$nShortDesc',
												longDesc='$nLongDesc', imagePath='$imagePath', qrCodePath='$nQRCodePath', itemTag='$nItemTag'
										 WHERE 	productID='$nProductID'";
						
						// Execute sqlUpdateRow
						if (mysqli_query($conn, $sqlUpdateRow)){
							// SQL query executed successfully
							echo "<p class='text-center alert alert-success'>Total rows updated: " . $conn->affected_rows . "</p>";
						}
						else {
							// SQL query fail
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>" . $sqlUpdateRow . "<br>" . mysqli_error($conn) . "</p>";
						}
					}
					else {
						// Do NOT update the record because there was an issue uploading the new image.
						echo "<p class='text-center alert alert-danger'><strong>Programmer Error 9: </strong>The record was not updated because an error occurred when attempting to upload the new image.</p>";
					}
					
					
					// Free up query results.
					mysqli_free_result($imgResults);
					?>
				</div>
			</div>	
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3">
					<a class="btn btn-primary pull-left navbar-btn" href="editItems.php">Edit Another Item</a>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
mysqli_close($conn);
include 'includes/footer.php';
?>