<?php 
session_start();
include 'includes/head.php';
include 'includes/header.php';
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
				echo "<p class='navbar-text'>Welcome, " .$_SESSION['username']. "!</p>";
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

	<?php
	
	//-------------connect to the database-------------
	$servername = 'mysql.objectsofdesirefindlay.com';
	$user             = 'jasrhu2';
	$password    = 'QRcodes21';
	$dbname       = 'qrusers';;

	$conn = new mysqli($servername, $user, $password, $dbname) or die("Unable to connect to the database");
	
	// error checking for adding the product and for uploading the file..
	// 1 = ok , 0 = error
	$uploadOk = 1;

	//--------------retrieve add form data--------------------
	$title                = addslashes($_POST["title"]);
	$price              = $_POST["price"];
	$shortDesc     = addslashes($_POST["short"]);
	$longDesc      = addslashes($_POST["detailed"]);
	$imageName  = $_FILES["fileToUpload"]["name"];
	if($imageName){
		$imagePath  = "uploads/" . $imageName;
	}
	else{
		$imagePath = "";
	}
	$QRCodeName = $_POST["qrtitle"];
	$QRCodePath   = "qruploads/" . $QRCodeName . ".png";
	$itemTag        	   = addslashes($_POST["itemTag"]);
	
	
	// --------------------- Add product to database ------------------
	// Example of what the below SQL query should look like:
	// INSERT INTO	products (title, price, shortDesc, longDesc, imagePath, qrCodePath)
	// VALUES 		("Product title", 5.00, "Product description", "detailed description", "Product image path", "QR code path", "itemTag")
	$sql = "INSERT INTO	products (title, price, shortDesc, longDesc, imagePath, qrCodePath, itemTag) 
			VALUES		(" . "\"" . $title . "\", " . $price . ", \"" . $shortDesc . "\",
						\"" . $longDesc . "\", \"" . $imagePath . "\", \"" . $QRCodePath . "\", \"" . $itemTag . "\")";
	
	if (mysqli_query($conn, $sql)){
		// SQL query executed successfully.
		echo "<center><p>New record created successfully</p></center>";
	} 
	else {
		// SQL query fail.
		echo "<p>Error: " . $sql . "<br>" . mysqli_error($conn) . "</p>";
		echo "<p>File will not be uploaded.</p>";
		$uploadOk = 0;
	}

	// Retrieve the productID for the newly entered item.
	// This will be use to generate a correct URL for this item.
	$sql = "SELECT  productID
			FROM	products	
			WHERE	title = \"" . $title . "\""
	;
	$results = mysqli_query($conn, $sql);
	$productID = $results->fetch_assoc();
	$productID = $productID['productID'];
	
	
	// -------------------- Generate QR Code -----------------------
	// Check if a QR code already exist with this title.
	if (!file_exists($QRCodePath)) {
		// Check if product was added to the database successfully before
		// creating a QR code for it.
		if ($uploadOk == 1) {
			// generate a QR code for this products.
			QRcode::png("http://www.objectsofdesirefindlay.com/displayItem.php?productID=" . $productID, $QRCodePath, "H", 4, 4);
			echo "<div class='row'>";
			echo "<center><img class='img-responsive' alt='Brand' src='$QRCodePath' width='200px'></center>";
			echo "</div>";
		}
		else {
			echo "<p>Cannot create QR code because there was an error adding the product.</p>";
		}
	}
	else {
		echo "<p>A QR code already exist with the title " . $QRCodeName . "</p>";
	}
	
	mysqli_close($conn); // close database connection.
	
	
	//--------------------- Upload the image to the uploads folder ---------------------------
	// if no image is select then do not attempt to upload.
	if ($imageName){
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
				echo "<p>Error: File is not an image.</p>";
				$uploadOk = 0;
			}
		}
		
		// Check if file already exists.
		if (file_exists($target_file)){
			echo "<p>Error: File already exists.</p>";
			$uploadOk = 0;
		}
		
		// Check file size - unit is in bytes (5MB).
		if ($_FILES["fileToUpload"]["size"] > 5000000){
			echo "<p>Error: Your file is above the 5MB limit.</p>";
			$uploadOk = 0;
		}
		
		// Limit the type of file formats.
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"){
			echo "<p>Error: Only JPG, JPEG, and PNG files are allowed.</p>";
			$uploadOk = 0;
		}
		
		// Check if $uploadOk has been set to 0 by an error.
		if ($uploadOk == 0){
			echo "<p>Your file was not uploaded.</p>";
		} 
		// If everything is ok, try to upload file.
		else{
			if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
				// No need to tell the user this succeeded, only if it failed.
				//echo "<p>The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.</p>";
			} 
			else {
				echo "<p>ERROR: There was an error uploading your file.</p>";
			}
		}
	}
	?>
	<?php   
		echo "<center><a class='btn btn-warning' style='margin:10px' href='./" . $QRCodePath . "' download='' ><span class='glyphicon glyphicon-qrcode' aria-hidden='true'></span> Download QR Code</a></center>";
	?>

	<center><a class="btn btn-success" style="margin:10px" href="addForm.php"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Another Item</a></center>
</div>

<?php include 'includes/footer.php';?>