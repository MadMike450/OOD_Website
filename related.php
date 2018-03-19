<?php
include 'includes/functions.php';

// Connect to the database
$conn = db_connector();
	

$page 		=	isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage	= 	isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : 10;

//Positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

$itemTag    = $_GET['rel'];
$prevProdID = $_GET['productID'];
$noRelated  = FALSE;

if($itemTag == "''"){
	// No related items to this product.
	$noRelated = TRUE;
}
else{
	//Query
	$sql = "SELECT  SQL_CALC_FOUND_ROWS *
			FROM	products	
			WHERE	itemTag = $itemTag
					AND productID <> $prevProdID
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
	<!-- header logo and buttons -->
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php?page=1">
					<img class="img-responsive" alt="Brand" src="./images/logo.jpg" width="100px">
				</a>
			</div>
			<?php echo "<a class='btn btn-default pull-left navbar-btn' href='./displayItem.php?productID=$prevProdID'>Back</a>" ?>
			<a class="btn btn-default pull-right navbar-btn" href="./index.php?page=1">Home</a>
			<!-- button position -->
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