<!DOCTYPE html>
<html lang="en">


<?php 
session_start();
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
	
	<?php
	// Connect to the database
	$conn = db_connector();
	
	// Retrieve the item tags.
	$sql = "SELECT DISTINCT itemTag FROM products";
	$results = mysqli_query($conn, $sql);
	?>
	
	<div id="main">
		<div class="container">
			
			<center><h2>Add Item</h2></center>
			
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12 col-md-8 col-md-offset-2" style="margin-bottom:10px">
					<form id="addItem" class="form-horizontal" action="addRecord.php" method="POST" enctype="multipart/form-data" >
						
						<div class="form-group">
							<label for="title">Title:</label>
							<input type="text" id="title" class="form-control" name="title" required />
						</div>
						
						<div class="form-group">
							<label for="price">Price:</label>
							<input type="text" id="price" class="form-control" name="price" required />
						</div>
						
						<div class="form-group">
							<label for="short">Short Description:</label>
							<textarea id="short" class="form-control" name="short" cols="40" rows="3" required></textarea>
						</div>
						
						<div class="form-group">
							<label for="detailed">Detailed Description:</label>
							<textarea id="detailed" class="form-control" name="detailed" cols="40" rows="5"></textarea>
						</div>
						
						<div class="form-group">
							<label for="qr">QR Code Title:</label>
							<input type="text" id="qr" class="form-control" name="qrtitle" required />
						</div>
						
						<div class="form-group">
							<input type="file" id="fileToUpload" name="fileToUpload" style="margin:20px; margin-left:0px" />
						</div>
						
						<div class="form-group">
							<div class="row">
								<div class="col-lg-6">
									<label for="qr">Tag Name:</label>
									<div class="input-group">
										
										<input type="text" id="itemTag" class="form-control" name="itemTag" />
										
										<div class="input-group-btn">
											
											<button type="button" id="dropdownMenu" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
												Tags
												<span class="caret"></span>
											</button>
											
											<ul class="dropdown-menu pull-right" role="menu">
												<?php while($itemTag = mysqli_fetch_assoc($results)){ ?>
													<li role="presentation"><a role="menuitem" href="javascript:return false;" onclick="selectTag()" tabindex="-1"><?php echo $itemTag['itemTag']; ?></a></li>
												<?php } ?>
											</ul>
										
										</div><!-- btn-group -->
									</div><!-- input-group -->
								</div><!-- col-lg-6 -->
							</div><!-- row -->
						</div><!--form-group-->	
						
						<div class="form-group">
							<input type="submit" class="btn btn-info pull-left" value="Submit" />
						</div>
					
					</form>	
				</div><!-- col -->
			</div><!-- row -->
		</div><!-- container -->
	</div><!-- main -->

<script type="text/javascript">
$(function(){
  
  $(".dropdown-menu pull-right li a").click(function(){
    
    $("#itemTag").text($(this).text());
     $("#itemTag").val($(this).text());
  });

});


//function selectTag() {
//  var selText = (this).value;
//  document.getElementById("itemTag").value=selText;
//}
</script>

</div>

<?php include 'includes/footer.php';?>