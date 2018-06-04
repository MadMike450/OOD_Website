<?php
session_start();
include 'includes/header.php';
include 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<div id="wrap">
	
	<!-- NEW header logo, header buttons, and session -->
	<nav class="navbar navbar-default">
		<div class="container">
			
			<div class="navbar-header">
				<a class="navbar-brand" href="./index.php?page=1">
					<img class="img-responsive" alt="Brand" src="./images/logo.jpg" width="100px">
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
				//die ("You must be logged in!");
			}
			?>
		</div>
	</nav>
	
	<div id="main">
		<div class="container">
						
			<h2></h2>
					
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
					
							<!-- related items will be related.php variable is rel -->
							<div class='row'>
								<div class='box-icon'>
									<center><img class='img-responsive' alt='Brand' src='<?php echo $row["imagePath"] ?>' /></center>
								</div>
							</div>
							<div class='row'>
								<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 col-md-offset-3'>
									<div class='box'>
										<div class='info'>
											<h4 class='text-center'><?php echo $row["title"] ?></h4>
											<h6 class='text-center'>$<?php echo $row["price"]?></h6>
											<h6 class='text-center'><?php echo $row["shortDesc"]?></h6>
											<p><?php echo $row["longDesc"]?></p>
											<a class='btn btn-small btn-success' href=/related.php?rel='"  . urlencode($row["itemTag"]) . "'&productID=" . urlencode($row['productID']) . "> Related Items  <span class='glyphicon glyphicon-search' aria-hidden='true'></span></a>
										</div>
									</div>
								</div>
					
					<?php	
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