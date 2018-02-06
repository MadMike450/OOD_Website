<?php 
session_start();
include 'includes/head.php';
include 'includes/header.php';
include('modalScript.php');
?>
<?php
			/*//-------------connect to the database-------------
			$servername = 'mysql.objectsofdesirefindlay.com';
			$user       = 'jasrhu2';
			$password   = 'QRcodes21';
			$dbname     = 'qrusers';
			
			$conn = new mysqli($servername, $user, $password, $dbname) or die("Unable to connect to the database");

			$page 		=	isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$perPage	= 	isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : 10;

			//Positioning
			$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
			
			 $sql = "SELECT  SQL_CALC_FOUND_ROWS productID, title 
				FROM	products
				ORDER BY title
				LIMIT	{$start}, {$perPage}";


			$result = $conn->query($sql);
			$total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];

			$pages = ceil($total/$perPage);*/
			
			//$eventRecords = mysql_query($query);
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
			<a class="btn btn-default pull-left navbar-btn" href="./dashboard.php">Dashboard</a>
			<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
		</div>
	</nav>
	
	<div id="main">
		<div class="container">
			
			<center><h2>Edit Item</h2></center>
			
			<?php
			//-------------connect to the database-------------
			$servername = 'mysql.objectsofdesirefindlay.com';
			$user       = 'jasrhu2';
			$password   = 'QRcodes21';
			$dbname     = 'qrusers';
			
			$conn = new mysqli($servername, $user, $password, $dbname) or die("Unable to connect to the database");

			$page 		=	isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$perPage	= 	isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : 10;

			//Positioning
			$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

			
			
			
			//-------------------- Populate variables from edit form --------------------
			$productID  = $_POST['productID'];
			$title      = $_POST['title'];
			$price      = $_POST['price'];
			$shortDesc  = $_POST['short'];
			$longDesc   = $_POST['detailed'];
			$imageName  = $_FILES["fileToUpload"]["name"];
			$qrCodePath = $_POST['qrtitle'];
			$itemTag	= $_POST['itemTag'];
			
			// Retrieve image path in case the same image is used or to delete the
			// image if a new one is uploaded.
			$imgQuery = "SELECT imagePath
						 FROM	products
						 WHERE	title = '" . $title . "'";
			if ($imgResults = mysqli_query($conn, $imgQuery)){
				$imagePath  = $imgResults->fetch_assoc();
				$imagePath  = $imagePath['imagePath'];
			}
			else {
				// ImagePath query failed.
				echo "<p>ERROR: SQL query for the imagePath failed.</p>";
			}
			
			// Check if a new image was selected.
			if ($imageName){
				// New image selected, delete the old one first.
				if ($imagePath){
					// If item did not contain an image then no 
					// need to remove anything from server.
					unlink($imagePath);
				}
				$newImage = 1;
				$imagePath = "uploads/" . $imageName;
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
				else {
					if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)){
						// No need to tell the user this succeeded, only if it failed.
						//echo "<p>The file " . basename( $_FILES["fileToUpload"]["name"]) . " has been uploaded.</p>";
					} 
					else {
						echo "<p>ERROR: There was an error uploading your file.</p>";
					}
				}
			}
			
			
			//------------------------ Update the product with the new info -------------------------------
			$sqlUpdateRow = "UPDATE products SET title='$title', price='$price', shortDesc='$shortDesc',
									longDesc='$longDesc', imagePath='$imagePath', qrCodePath='$qrCodePath', itemTag='$itemTag'
							 WHERE 	productID='$productID'";
			
			// Execute sqlUpdateRow
			if (mysqli_query($conn, $sqlUpdateRow)){
				// SQL query executed successfully
				echo 'Total rows updated: ' . $conn->affected_rows;
			}
			else {
				// SQL query fail
				echo "<p>Error: " . $sqlUpdateRow . "<br>" . mysqli_error($conn) . "</p>";
			}
			
			
			//------------------- Redisplay all the products with the update info ---------------------
			
			
			//$query = "SELECT * FROM products ORDER BY title";
			//Query
			 $sql = "SELECT  SQL_CALC_FOUND_ROWS productID, title 
				FROM	products
				ORDER BY title
				LIMIT	{$start}, {$perPage}";


			$result = $conn->query($sql);
			$total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];

			$pages = ceil($total/$perPage);
			
			//$eventRecords = mysql_query($query);
			
			?>    
			
			<div class="table-responsive">
				<table class="table table-striped">
					<tr>
					<th>Title</th>
					<th>Details</th>
					<th>Edit</th>
					</tr>
				
					<?php
						if ($productRecords = $conn->query($sql)){
							while($productArray = $productRecords->fetch_assoc()){
								
								echo "<tr>";
								echo "<td>".$productArray['title']."</td>";
								/*echo "<td>".$productArray['price']."</td>";
								echo "<td>".$productArray['productID']."</td>";
								echo "<td>".$productArray['shortDesc']."</td>";*/
								
								
								echo "<td><center><a href='#' class='btn btn-info edit-record' data-toggle='modal' data-target='#myModal' data-id=".$productArray['productID']."><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></a></center></td>";
								
								
								
								
								echo "<td><a class='btn btn-warning btn-xs' href='updateForm.php?edit=$productArray[productID]'><span class='glyphicon glyphicon-pencil' aria-hidden='true'></span></a></td>";
								
								echo "</tr>";

							}//end while
						}
						$productRecords->free();
						mysqli_close($conn); // close database connection
						
					?>
					
				</table>
					
			</div>
		
			<div>
				<div class="text-center">
					<ul class="pagination">
						<li>
							
							<?php
							$firstPage    = 1;
							$currentPage   = (int)$_GET['page'] ;
							$previousPage  = $currentPage - 1;
							
							if($currentPage > $firstPage):

							?>
								<a href="?page=<?php echo $previousPage ?>" aria-label="Previous">
								<span aria-hidden="true">&laquo;</span></a>
							
								<?php else: ?>
							
								<span class="disabled" aria-hidden="true">&laquo;</span>
						
							<?php endif; ?>
						</li>
						
						<?php for($x = 1; $x <= $pages; $x++): ?>
	   
							<li <?php if($page === $x):?> class="active"<?php endif;?>>
							
								
							
								<a href="?page=<?php echo $x?>&per-page=<?php echo $perPage; ?>">
								<?php echo $x?>
								</a>
							</li>
	   
						<?php endfor;?>
						<li>
							<?php
							$currentPage = (int)$_GET['page'];
							$nextPage    = $page + 1;
							if($currentPage < $x - 1): ?>
							
							  <a href="?page=<?php echo $nextPage ?>" aria-label="Next">
								<span aria-hidden="true">&raquo;</span>
							  </a>
							  <?php else: ?>
							  <span class="disabled" aria-hidden="true">&raquo;</span>
							<?php endif; ?>
						</li>
					</ul>
				</div>
			</div>
			
		<!----------------modal pop up with details------------------->
		<?php 
		include('detailModal.php');
		?>
		
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>



