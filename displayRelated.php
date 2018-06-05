<?php
session_start();
include 'includes/functions.php';

// Connect to the database
$conn = db_connector();
	

$page 		=	isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage	= 	isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : 10;

//Positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

$itemTag    = $_GET['rel'];
$origProdID = $_GET['productID'];
$noRelated  = FALSE;

if($itemTag == "''"){
	// No related items to this product.
	$noRelated = TRUE;
}
else{
	//Query
	$sql = "SELECT  SQL_CALC_FOUND_ROWS *
			FROM	products	
			WHERE	itemTag = '$itemTag'
			AND     productID <> '$origProdID'
			LIMIT	{$start}, {$perPage}";
	$result = mysqli_query($conn, $sql);
	if (mysqli_num_rows($result) == 0){
		// Contains a tag name, but no other items contain the same tag name.
		$noRelated = TRUE;
	}
}
$total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];

$pages = ceil($total/$perPage);



?>


<!DOCTYPE html>
<html lang="en">
<?php 
include 'includes/header.php';
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
				<a class="btn btn-default pull-left navbar-btn" href="./displayItem.php?productID=<?php echo $origProdID; ?>">Back</a>
				<a class="btn btn-default pull-right navbar-btn" href="./logout.php">Log Out</a>
			<?php } else{ ?>
				<a class="btn btn-default pull-left navbar-btn" href="./index.php?page=1">Home</a>
				<a class="btn btn-default pull-left navbar-btn" href="./displayItem.php?productID=<?php echo $origProdID; ?>">Back</a>
				<a class="btn btn-default pull-right navbar-btn" href="./loginPage.php">Log In</a>
			
			<?php	
				//die ("You must be logged in!");
			}
			?>
		</div>
	</nav>
	
	<div id="main">
		<div class="container">
			
			<h2>Related Items</h2>
			
			<!-- product info form -->
			<div class="row">
				<div class="col-xs-12" style="margin-bottom:10px">
					
					<?php
					if (!$noRelated){					
						foreach($result as $results): 
					?>
					<div class="result">
						<form class="form-inline" action="displayItem.php" method="GET" enctype="multipart/form-data" id="displayItem"/>
							<div class="table-responsive">
								<?php 
				
								echo '<table class="table table-striped">';
								echo '<tr>';
								echo "<td><a href=/displayItem.php?productID=" . $results['productID'] . '>'. $results['title'] .  "</a></td>";
								echo '</tr>';
								
								echo '</table>';
								?> 
							</div>
						</form>
					</div>
					<?php 
						endforeach;
					} // End $noRelated if statement.
					else {
						echo "<p>This item has no related items.</p>";
					}
					?>
				</div>
				<div class="col-xs-4" style="margin-bottom:10px">
					
				</div>
			</div>	
		</div>
			
	
	</div>
</div>
<?php include 'includes/footer.php';?>