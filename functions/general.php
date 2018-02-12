<?php

// Function to connect to my database.
function db_connector(){
	$servername = 'localhost';
	$user       = 'root';
	$password   = 'abc123';
	$dbname     = 'test_db_ood_website';
	
	$conn = new mysqli($servername, $user, $password, $dbname) or die("ERROR in general.php db_connector function.  Unable to connect to the database");
	return $conn;
}

/*
// When uploading an image, if the current image to be uploaded has the same file name as an existing
//    image on the server, then this function will update the file name by adding a number to the
//    end of the file name.
// A possible modification may need to be made to handle when this feature is turned off.
function autoChgImageName($productID, $imageFilename, $imagePath){
	$switch   = 1;
	$uploadOK = 1;
	
	if ($switch){
		// Auto change the image name if an image with the same file name already exist on the server.
		if (file_exists($imagePath)) {
			$i = 0;
			$newTargetFile = $imagePath;
			while (file_exists($newTargetFile)){
				$i++;
				$tempExt  = explode('.', $imageFilename);
				$tempExt  = end($tempExt);
				$tempName = explode('.', $imageFilename, -1);
				$newFilename = "";
				foreach ($tempName AS $str) // Put the file name back together
					$newFilename = $newFilename . $str;
				$newFilename = $newFilename . "(" . $i . ")." . $tempExt;
				$newTargetFile = $defaultImageDir . $newFilename;
			}
			echo "<p>Your new file name is <strong>" . $newFilename . "</strong></p>";
			$imagePath = $newTargetFile;
			
			$sql = "UPDATE products
					SET    imagePath = '$imagePath'
					WHERE  productID = '$productID'";
			if (!mysqli_query($conn, $sql)) {
				$uploadOK = 0;
				echo "<p class='alert alert-danger'>Programmer Alert 3: Update query failed to modify the image path to reflect the new file name (<strong>" . $newFilename . "</strong>)</p>";
			}
		}
	}
	else { // Do not auto modify the image name. Do not upload the image and display an error to the user.
		if (file_exists($imagePath)){
			echo "<p class='alert alert-danger'>ALERT 4: An image with the name <strong>" . $imageFilename . "</strong> already exists.</p>";
			$uploadOk = 0;
		}
	}
	
	return $uploadOK;
}
*/


?>