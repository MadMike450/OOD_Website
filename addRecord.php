<?php 
session_start();
include 'includes/header.php';
include 'functions/general.php';
include 'includes/phpqrcode/qrlib.php';
?>

<!DOCTYPE html>
<html lang="en">

<div id="wrap">
	<!-- header logo, header buttons, and session -->
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
				die ("ERROR 888: You must be logged in!");
			}
			?>
			<a class="btn btn-default pull-left navbar-btn" href="./dashboard.php">Dashboard</a>
			<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
		</div>
	</nav>
	
	
	<div class="row">
		<div class="text-center col-xs-12 col-sm-12 col-md-4 col-lg-4 col-md-offset-4" style="margin-bottom:10px">
			<?php
			// Display (1) or hide (0) debug alerts.  For testing only.
			$debugAlerts = 1;
			
			// Connect to the database
			$conn = db_connector();
			
			// This variable is just an on off switch for a feature. This feature will automatically modify the file name of an image the user is trying to upload
			//   if a file already exist on the server with that name. (On = 1 : Off = 0)
			$autoChgImageName = 1;
			
			// error checking for adding the product and for uploading the file.
			// 1 = ok , 0 = error
			$uploadOk = 1;
			
			// Error checking for the creation of the QR code.
			// 1 = yes , 0 = no
			$QRCodeCreated = 1;
			
			// Default path for image uploads
			$defaultImageDir = "uploads/";
			
			// If no picture is selected for the item then we will use a default
			//   image that says "no image available."
			$defaultImagePath = $defaultImageDir . "noimage.png";
			
			
			
			//--------------retrieve add form data--------------------
			$title     = mysqli_real_escape_string($conn, $_POST["title"]);
			$price     = (int)$_POST["price"];
			$shortDesc = mysqli_real_escape_string($conn, $_POST["short"]);
			$longDesc  = mysqli_real_escape_string($conn, $_POST["detailed"]);
			$imageName = mysqli_real_escape_string($conn, $_FILES["fileToUpload"]["name"]);
			if($imageName){
				$imagePath = $defaultImageDir . $imageName;
			}
			else{
				$imagePath = $defaultImagePath;
			}
			$QRCodeName = mysqli_real_escape_string($conn, $_POST["qrtitle"]);
			$QRCodePath = "qruploads/" . $QRCodeName . ".png";
			$itemTag    = mysqli_real_escape_string($conn, $_POST["itemTag"]);
			
			
			
			// --------------------- Add product to database ------------------
			// Example of what the below SQL query should look like:
			// INSERT INTO	products (title, price, shortDesc, longDesc, imagePath, qrCodePath)
			// VALUES 		("Product title", 5.00, "Product description", "detailed description", "Product image path", "QR code path", "itemTag")
			$sql = "INSERT INTO	products (title, price, shortDesc, longDesc, imagePath, qrCodePath, itemTag) 
					VALUES		('$title', $price, '$shortDesc', '$longDesc', '$imagePath', '$QRCodePath', '$itemTag')";
			
			if (mysqli_query($conn, $sql)){
				// SQL query executed successfully.
				echo "<p class='alert alert-success'>New item added successfully</p>";
			} 
			else {
				// SQL query fail.
				// New Feature: create a log file and write these kind of technical errors to the log.  Then just display an error saying to contact your systems administrator.
				if($debugAlerts)
					echo "<p class='alert alert-danger'>Debug Alert 5: SQL query failed.  The record was not uploaded." . <br> . $sql . "<br>" . mysqli_error($conn) . "</p>";
				echo "<p class='alert alert-danger'>Error 5: The record was not uploaded.</p>";
				$uploadOk = 0;
			}
			
			
			
			// Retrieve the productID for the newly entered item for the QR code path.
			// This will be use to generate a correct URL for this item.
			$sql = "SELECT productID
					FROM   products	
					WHERE  title = '$title'";
			$results   = mysqli_query($conn, $sql);
			$productID = $results->fetch_assoc();
			$productID = $productID['productID'];
			
			// -------------------- Generate QR Code -----------------------
			// Check if a QR code already exist with this title.
			if (!file_exists($QRCodePath)) {
				// Check if product was added to the database successfully before
				// creating a QR code for it.
				if ($uploadOk === 1) {
					// generate a QR code for this products.
					QRcode::png("http://www.objectsofdesirefindlay.com/displayItem.php?productID=" . $productID, $QRCodePath, "H", 4, 4);
				}
				else {
					$QRCodeCreated = 0;
					echo "<p class='alert alert-danger'>Error 10: A QR code was not created because there was an error adding the product.</p>";
				}
			}
			else {
				$QRCodeCreated = 0;
				echo "<p class='alert alert-warning'>Error 15: A QR code was NOT created for this item because one already exist with the title <strong>" . $QRCodeName . "</strong></p>";
				
				// NEW FEATURE - remove the entire record and do not continue on because we couldn't create the QR code (and thats the entire point of this website).
				
				// Remove the QR code path since no QR code was created.
				$sql = "UPDATE products 
						SET    qrCodePath = ''
						WHERE  productID = '$productID'";
				if (!mysqli_query($conn, $sql)){
					if($debugAlerts)
						echo "<p class='alert alert-danger'>Debug Alert 10: A QR code file path is erroneously attached to this item and the system failed to remove it.</p>";
				}
				
			}
			
			
			
			//--------------------- Upload the image to the uploads folder ---------------------------
			// if no image was selected then do not attempt an upload.
			if ($imageName){
				
				// This debug alert is useful for catching when the upload_max_filesize is set too small in the php.ini file.
				if($debugAlerts){
					if($_FILES["fileToUpload"]["name"] && !$_FILES["fileToUpload"]["tmp_name"]){
						echo "<p class='alert alert-danger'>Programmer Alert 3-1: FILES[name] = <strong>" . $_FILES["fileToUpload"]["name"] . "</strong></p>";
						echo "<p class='alert alert-danger'>Programmer Alert 3-2: FILES[tmp_name] = <strong>" . $_FILES["fileToUpload"]["tmp_name"] . "</strong></p>";
						echo "<p class='alert alert-danger'>Programmer Alert 3-3: FILES[name] is populated, but FILES[tmp_name] is empty.  This is probably because your upload_max_filesize is set to small in the php.ini file.</p>";
					}
				}
				
				// CHECK #1: Check if image file is actually an image.
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false){
					if($debugAlerts)
						echo "<p class='alert alert-success'>Debug Alert 15: File is an image - <strong>" . $check["mime"] . "</strong></p>";
					$uploadOk = 1;
				} else {
					echo "<p class='alert alert-danger'>Error 20: The selected file is not an image.</p>";
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
							$tempExt  = explode('.', $imageName);
							$tempExt  = end($tempExt);
							$tempName = explode('.', $imageName, -1);
							$newFilename = "";
							foreach ($tempName AS $str) // Put the file name back together
								$newFilename = $newFilename . $str;
							$newFilename = $newFilename . "(" . $i . ")." . $tempExt;
							$newTargetFile = $defaultImageDir . $newFilename;
						}
						if($debugAlerts)
							echo "<p class='alert alert-warning'>The image file has been renamed to avoid duplicate file names.  The new file name is <strong>" . $newFilename . "</strong></p>";
						$imagePath = $newTargetFile;
						
						$sql = "UPDATE products
								SET    imagePath = '$imagePath'
								WHERE  productID = '$productID'";
						if (!mysqli_query($conn, $sql)) {
							$uploadOK = 0;
							if($debugAlerts)
								echo "<p class='alert alert-danger'>Debug Alert 20: The update query failed to modify the image path to reflect the new file name (<strong>" . $newFilename . "</strong>)</p>";
						}
					}
				}
				else { // Do not auto modify the image name. Do not upload the image and display an error to the user.
					if (file_exists($imagePath)){
						echo "<p class='alert alert-danger'>Error 25: An image with the file name <strong>" . $imageName . "</strong> already exists.</p>";
						$uploadOk = 0;
					}
				}
				
				
				// CHECK #3: Check file size - unit is in bytes (5MB).
				if ($_FILES["fileToUpload"]["size"] > 5000000){
					echo "<p class='alert alert-danger'>Error 30: Your image is above the 5MB limit.</p>";
					$uploadOk = 0;
				}
				
				
				// CHECK #4: Limit the type of file formats.
				$imageFileType = pathinfo($imagePath, PATHINFO_EXTENSION);
				if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
					echo "<p class='alert alert-danger'>Error 35: Only JPG, JPEG, and PNG images are allowed.</p>";
					$uploadOk = 0;
				}
				
				
				// Check if $uploadOk has been set to 0 by an error.
				if ($uploadOk === 0){
					echo "<p class='alert alert-danger'>Error 40: Your image was not uploaded.</p>";
				}
				else{ // If everything is ok, try to upload file.
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $imagePath)){
						if($debugAlerts)
							echo "<p class='alert alert-success'>Debug Alert 25: The image file <strong>" . basename($_FILES["fileToUpload"]["name"]) . "</strong> has been uploaded.</p>";
					} 
					else {
						echo "<p class='alert alert-danger'>Error 45: An error occurred while attempting to upload your image</p>";
						if($debugAlerts)
							echo "<p class='alert alert-danger'>Debug Alert 30: An error occurred during move_uploaded_file:<br>" . $_FILES["fileToUpload"]["error"] . "</p>";
					}
				}
			}
			?>
		
		</div>
	</div>
	
	
	<!-- Display QR code and Download QR Code button -->
	<?php
	if ($QRCodeCreated === 1){
		echo "<div class='row'><center><img class='img-responsive' alt='Brand' src=$QRCodePath width='200px'></center></div>";
		echo "<center><a class='btn btn-warning' style='margin:10px' href='./" . $QRCodePath . "' download><span class='glyphicon glyphicon-qrcode' aria-hidden='true'></span> Download QR Code</a></center>";
	}
	else{
		echo "<center><a class='btn btn-warning disabled' style='margin:10px' href=''><span class='glyphicon glyphicon-qrcode' aria-hidden='true'></span> Download QR Code</a></center>";
	}
	?>
	
	<!-- Display Add Another Item button -->
	<center><a class="btn btn-success" style="margin:10px" href="addForm.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Another Item</a></center>
	
	<!-- 
	New Feature:
	Add a button to remove the item that was just added and try again.  Immediately redirect the user to the add form after deleting this item.  This button needs to be
	available if any error occurred (i.e. image wasn't uploaded or the qr code wasnt created successfully).
	
	- The productID has already been retrieved and can easily be used to remove the item.
	- data close will need to be move to the bottom of this page so we can perform the remove.
	
	-->
	
	
</div>

<?php
mysqli_close($conn); // close database connection.
include 'includes/footer.php';
?>