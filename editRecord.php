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
					
					// error checking for adding the product and for uploading the file.
					// 1 = ok , 0 = error
					$uploadOk = 1;
					
					// This variable is just an on off switch for a feature. This feature will automatically modify the file name of an image the user is trying to upload
					//   if a file already exist on the server with that name. (On = 1 : Off = 0)
					$autoChgImageName = 1;
					
					// Default path for image uploads
					$defaultImageDir = "uploads/";
					
					// This variable holds the default image path string. This image is only used if no image was selected
					//    when the item was added.
					$defaultImagePath = $defaultImageDir . "noimage.png";
					
					
					//-------------------- Populate variables from edit form --------------------
					$nProductID  = (int)$_POST['productID'];
					$nTitle      = mysqli_real_escape_string($conn, $_POST['title']);
					$nPrice      = (int)$_POST['price'];
					$nShortDesc  = mysqli_real_escape_string($conn, $_POST['shortDesc']);
					$nLongDesc   = mysqli_real_escape_string($conn, $_POST['longDesc']);
					$nImageName  = mysqli_real_escape_string($conn, $_FILES["fileToUpload"]["name"]);
					$nQRCodePath = mysqli_real_escape_string($conn, $_POST['qrtitle']);
					$nItemTag    = mysqli_real_escape_string($conn, $_POST['itemTag']);
					
					
					//-------------------- Retrieve the old image path. --------------------
					// Retrieve image path in case the same image is used or to delete the
					// image if a new one is uploaded.
					$imgQuery = "SELECT imagePath
								 FROM	products
								 WHERE	productID = " . $nProductID;
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
							if (file_exists($imagePath)){
								unlink($imagePath);
							}
							else {
								// Image not found on server, image not removed. (image probably does not exist, so no real issue here).
								echo "<p class='text-center alert alert-warning'><strong>WARNING: </strong>The original image was not removed from the server because the image was not found.</p>";
							}
						}
						$newImage  = 1;
						$imagePath = $defaultImageDir . $nImageName;
					}
					else {
						// Using the same image.
						$newImage = 0;
					}
					
					
					//----------------------- Upload the new image to the uploads folder ------------------------------------------
					// Do not need to perform upload if original image is used.
					if ($newImage){
						
						// CHECK #1: Check if image file is actually an image or fake image.
						$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
						if($check !== false){
							//echo "<p class='alert alert-success'>File is an image - <strong>" . $check["mime"] . "</strong></p>";
							$uploadOk = 1;
						} else {
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>File is not an image.</p>";
							$uploadOk = 0;
						}
						
						
						// CHECK #2: Check if an image with this file name already exist on the server.
						if ($autoChgImageName){
							// Auto change the image name if an image with the same file name already exist on the server.
							if (file_exists($imagePath)) {
								$i = 0;
								$newTargetFile = $imagePath;
								while (file_exists($newTargetFile)){
									$i++;
									$tempExt  = explode('.', $nImageName);
									$tempExt  = end($tempExt);
									$tempName = explode('.', $nImageName, -1);
									$newFilename = "";
									foreach ($tempName AS $str) // Put the file name back together
										$newFilename = $newFilename . $str;
									$newFilename = $newFilename . "(" . $i . ")." . $tempExt;
									$newTargetFile = $defaultImageDir . $newFilename;
								}
								echo "<p class='text-center alert alert-warning'>Your new file name is <strong>" . $newFilename . "</strong></p>";
								$imagePath = $newTargetFile;
								
								$sql = "UPDATE products
										SET    imagePath = '$imagePath'
										WHERE  productID = '$nProductID'";
								if (!mysqli_query($conn, $sql)) {
									$uploadOK = 0;
									echo "<p class='alert alert-danger'>Programmer Alert 3: Update query failed to modify the image path to reflect the new file name (<strong>" . $newFilename . "</strong>)</p>";
								}
							}
						}
						else { // Do not auto modify the image name. Do not upload the image and display an error to the user.
							if (file_exists($imagePath)){
								echo "<p class='alert alert-danger'>ALERT 4: An image with the name <strong>" . $nImageName . "</strong> already exists.</p>";
								$uploadOk = 0;
							}
						}
						
						
						// CHECK #3: Check file size - unit is in bytes (5MB).
						if ($_FILES["fileToUpload"]["size"] > 5000000){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>Your file is above the 5MB limit.</p>";
							$uploadOk = 0;
						}
						
						
						// CHECK #4: Limit the type of file formats.
						$imageFileExt = pathinfo($imagePath,PATHINFO_EXTENSION);
						if($imageFileExt != "jpg" && $imageFileExt != "png" && $imageFileExt != "jpeg"){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>Only JPG, JPEG, and PNG files are allowed.</p>";
							$uploadOk = 0;
						}
						
						
						// Check if $uploadOk has been set to 0 by an error.
						if ($uploadOk == 0){
							echo "<p class='text-center alert alert-danger'><strong>ERROR: </strong>Your file was not uploaded.</p>";
						} 
						// If everything is ok, try to upload file.
						else {
							if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $imagePath)){
								// This alert is kind of necessary when doing an EDIT because it will say 0 rows updated if the only thing you updated is the image. This would
								//    would be very misleading to the end users. Now at least it will inform them the image uploaded successfully.
								echo "<p class='text-center alert alert-success'>The new image file <strong>" . $nImageName . "</strong> has been uploaded successfully.</p>";
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