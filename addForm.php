<?php 
session_start();
include 'includes/head.php';
include 'includes/header.php';
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
	$dbname       = 'qrusers';

	$conn = new mysqli($servername, $user, $password, $dbname) or die("Unable to connect to the database");
	
	// Retrieve the item tags.
	$sql = "SELECT DISTINCT itemTag FROM products";
	$itemTagArr = mysqli_query($conn, $sql);
	?>
	
	<div id="main">
		<div class="container">
			
			<center><h2>Add Item</h2></center>
			
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2" style="margin-bottom:10px">
					<form class="form-horizontal" action="addItem.php" method="POST" enctype="multipart/form-data" id="addItem"/>
						<div class="form-group">
							<label for="title">Title:</label><input class="form-control" type="text" id="title" name="title" required/>
						</div>
						<div class="form-group">
							<label for="price">Price:  </label><br><input class="form-control" id="price" type="text" name="price"/ required>
						</div>
						<div class="form-group">
							<label for="short">Short Description: </label>
							<textarea class="form-control" id="short" class="" cols="40" rows="3" name="short" form="addItem" required></textarea>
						</div>
						<div class="form-group">
							<label for="detailed">Detailed Description: </label>
							<textarea class="form-control" id="detailed" cols="40" rows="5" name="detailed" form="addItem"></textarea>
						</div>
						<div class="form-group">
							<label for="qr">QR Code Title:</label><input class="form-control" id="qr" type="text" name="qrtitle" required/>
						</div>
						<div class="form-group">
							<input style="margin:20px; margin-left:0px" type="file" name="fileToUpload" id="fileToUpload">
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="qr">Tag Name:</label>
									<div class="input-group">
										<input type="text" class="form-control" type="text" id="itemTag" name="itemTag" aria-label="...">
										
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
							<input class="btn btn-info pull-left" type="submit" value="Submit">
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