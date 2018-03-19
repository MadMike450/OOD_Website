<?php 
session_start();
include 'includes/header.php';
include 'includes/functions.php';
include 'includes/modalScript.php';
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
			
			<center><h2>Download QR Codes</h2></center>
			
			<?php
			// Connect to the database
			$conn = db_connector();

			$page 		=	isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$perPage	= 	isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : 5;

			//Positioning
			$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;
			
			//------------------- Redisplay all the products with the update info ---------------------
			
			//$query = "SELECT * FROM products ORDER BY title";
			//Query
			$sql = "SELECT  SQL_CALC_FOUND_ROWS productID, title, qrCodePath 
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
					<th>QR Code</th>
					</tr>
				
					<?php
						if ($productRecords = $conn->query($sql)){
							while($productArray = $productRecords->fetch_assoc()){
							
								$QRCodePath =$productArray['qrCodePath'];

								echo "<tr>";
								echo "<td>".$productArray['title']."</td>";
								/*echo "<td>".$productArray['price']."</td>";
								echo "<td>".$productArray['productID']."</td>";
								echo "<td>".$productArray['shortDesc']."</td>";*/
								
								
								echo "<td><a href='#' class='btn btn-info edit-record' data-toggle='modal' data-target='#myModal' data-id=".$productArray['productID']."><span class='glyphicon glyphicon-info-sign' aria-hidden='true'></span></a></td>";
								
								
								echo "<td><a class='btn btn-warning' href='./" . $QRCodePath . "' download='' ><span class='glyphicon glyphicon-save' aria-hidden='true'></span></a></td>";
								
								echo "</tr>";

							}//end while
						}
						$productRecords->free();
						mysqli_close($conn); // close database connection
					?>
				</table>
			</div>
			<!----------------modal pop up with details------------------->
			<?php include('detailModal.php'); ?>
		</div>
		
		<nav>
	<div class="text-center">
 <ul class="pagination">
  <li>
	<!---Functionality to navigate pages--->
	<?php
	$firstPage    = 1;
	$currentPage   = (int)$_GET['page'] ;
	$previousPage  = $currentPage - 1;
	if($currentPage > $firstPage):

	?>
      <a href="?page=<?php echo $previousPage ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
      <?php else: ?>
      <span class="disabled" aria-hidden="true">&laquo;</span>
      <?php endif; ?>
    </li>
 <?php for($x = 1; $x <= $pages; $x++): ?>
   
    <li<?php if($page === $x):?> class="active"<?php endif;?>><a href="?page=<?php echo $x?>&per-page=<?php echo $perPage; ?>"><?php echo $x?></a></li>
   
<?php endfor;?>
<li>
	<!---Functionality to navigate pages--->
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
</nav>

	</div>
</div>

<?php include 'includes/footer.php';?>