<!DOCTYPE html>
<html lang="en">

<!-- --------------------------------------------------------------------------------------- -->

<?php 
session_start();
include 'includes/header.php';
include 'includes/functions.php';

$id = $_GET['productID']; 

// This is the page number a back or cancel button will return you to.
$returnPage = isset($_GET['fromPage']) ? $_GET['fromPage'] : 'X';

// Get the original page number we came from on editItems page.
$returnPageNum = isset($_GET['fromPageNum']) ? $_GET['fromPageNum'] : 'X';

// Connect to the database
$conn = db_connector();   

$query = "SELECT * 
		  FROM	 products 
		  WHERE	 productID='$id'";

if ($productRecords = $conn->query($query)){
	$productArray = $productRecords->fetch_assoc();
}

// Retrieve the item tags.
$sql = "SELECT DISTINCT itemTag FROM products";
$itemTagArr = mysqli_query($conn, $sql);
?>

<!-- --------------------------------------------------------------------------------------- -->

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
				<a class="btn btn-default pull-left navbar-btn" href="./editItems.php?page=1">Back</a>
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
			
			<center><h2>Edit Item</h2></center>
			
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2" style="margin-bottom:10px">
					<form class="form-horizontal" action="editRecord.php" method="POST" enctype="multipart/form-data" id="editItem"/>
						<input id="productID" name="productID" type="hidden" value="<?php echo $productArray['productID']?>" class="form-control">
						<div class="form-group">
							<label for="title">Title:</label><input class="form-control" type="text" id="title" name="title" value="<?php echo $productArray['title']?>" required/>
						</div>
						<div class="form-group">
							<label for="price">Price:  </label><br><input class="form-control" id="price" type="text" name="price" value="<?php echo $productArray['price']?>" required/>
						</div>
						<div class="form-group">
							<label for="shortDesc" >Short Description: </label>
							<textarea class="form-control" id="shortDesc" class="" cols="40" rows="3" name="shortDesc" required/><?php echo $productArray['shortDesc']?></textarea>
						</div>
						<div class="form-group">
							<label for="longDesc" >Detailed Description: </label>
							<textarea class="form-control" id="longDesc" cols="40" rows="5" name="longDesc" ><?php echo $productArray['longDesc']?></textarea>
						</div>
						<div class="form-group">
							<label for="qr">QR Code Title:</label><input class="form-control" id="qr" type="text" name="qrtitle" value="<?php echo $productArray['qrCodePath']; ?>" readonly/>
						</div>
						<div class="form-group">
							<input style="margin:20px; margin-left:0px" type="file" name="fileToUpload" id="fileToUpload">
						</div>
						
						
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="qr">Tag Name:</label>
									<div class="input-group">
										<input type="text" class="form-control" type="text" id="itemTag" name="itemTag" value="<?php echo $productArray['itemTag']; ?>">
										
										<div class="input-group-btn">
											<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown" aria-expanded="true">
												Tags
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu pull-right" role="menu">
												<?php while($itemTag = mysqli_fetch_assoc($itemTagArr)){ ?>
												<li role="presentation"><a role="menuitem" tabindex="-1" href="javascript:return false;"><?php echo $itemTag['itemTag']; ?></a></li>
												<?php } ?>
											</ul>
										</div><!-- /btn-group -->
									</div><!-- /input-group -->
								</div><!-- /.col-lg-6 -->
							</div><!-- /.row -->
						</div><!--form-group-->	
						
						
						<div class="form-group">
							<input class="btn btn-info pull-left" type="submit" value="Update">
						</div>
					</form>	
				</div>
			</div>	
		</div>
	</div>
	
<script src="jquery/jquery.js"></script>
<script type="text/javascript" src='js/bootstrap.min.js'></script>
<script>
$(".dropdown-menu li a").click(function(){
  var selText = $(this).text();
  document.getElementById("itemTag").value=selText;
});
</script>

<link rel="stylesheet" href="css/bootstrap.css" />
	
</div>

<?php include 'includes/footer.php';?>