<?php
include 'includes/header.php';
include 'includes/general.php';

// Connect to the database
$conn = db_connector();


// Variable, calculations, and queries for pagination below.
$perPageDef  = 9;
$perPageMax  = 9;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage     = isset($_GET['per-page']) && $_GET['per-page'] <= $perPageMax ? (int)$_GET['per-page'] : $perPageDef;
$startItem   = ($currentPage > 1) ? ($currentPage * $perPage) - $perPage : 0;

// Query current page items
$sql = "SELECT   SQL_CALC_FOUND_ROWS *
		FROM	 products
		ORDER BY title
		LIMIT	 {$startItem}, {$perPage}";

$results    = $conn->query($sql);
$totalItems = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];
$totalPages = $totalItems > 0 ? ceil($totalItems/$perPage) : 0;
$prevPage   = $currentPage <= $totalPages ? $currentPage - 1 : $totalPages;
$nextPage   = $currentPage > 0 ? $currentPage + 1 : 1;
?>



<!DOCTYPE html>
<html lang="en">	

<div id="wrap">
	<!-- Header logo and buttons -->
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">
					<img class="img-responsive" alt="Brand" src="./images/logo.jpg" width="100px">
				</a>
			</div>
			<a class='btn btn-default pull-right navbar-btn' href='loginPage.php'>Log In</a>
		</div>
	</nav>
	
	<div id="main">
		<div class="container">
			
			<h3>Welcome to our shop!</h3>
			<div class="jumbotron">
				<p style="font-size:16px"> 
					Use a QR code scanner on your phone to scan the items and learn more about them.  If you don't have a phone or don't have a QR scanner installed, see us at the register.  We will kindly provide you with an iPod with a scanner installed.  Happy shopping!
				</p>
			</div>
			
			<!-- Product info form -->
			<div class="row">
				<!--<div class="col-xs-12" style="margin-bottom:10px">-->
				<?php foreach($results as $result): ?>
				
				<div class="result">
					<form class="form-inline" action="displayItem.php" method="GET" enctype="multipart/form-data" id="displayItem">
						<!-- <div class="table"> -->
						<div class="col-xs-12 col-sm-6 col-md-4">
							<div class="row">
								<div class="col-sm-12">
									<?php echo "<td><center><img style='margin-top:20px' class='img-responsive' alt='Brand' style='max-height: 150px' src='" . $result['imagePath'] . "'></center></td>";?>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<?php echo "<td><center><a href=/displayItem.php?productID=" . $result['productID'] . '>' . $result['title'] .  "</a></center></td>";?>
								</div>	
							</div>
						</div>
						<!-- </div> -->
					</form>
				</div>
				
				<?php
				endforeach;
				mysqli_free_result($results);
				?>
				<!-- </div> -->
			</div>	
			
			
			<!--- Pagination - page navigation bar --->
			<nav>
				<div class="text-center">
					<ul class="pagination">
						
						<!--- Set up the Previous button --->
						<li>
							<?php if($currentPage > 1 && $totalPages > 0): ?>
							<a href="?page=<?php echo $prevPage ?>&per-page=<?php echo $perPage ?>" aria-label="Previous">
								<span aria-hidden="false">&laquo;</span>
							</a>
							<?php else: ?>
							<span class="disabled" aria-hidden="true">&laquo;</span>
							<?php endif; ?>
						</li>
						
						<!-- Set up each individual page button -->
						<?php for($x = 1; $x <= $totalPages; $x++): ?>
						<li <?php if($currentPage === $x): ?> class="active"<?php endif; ?> >
							<a href="?page=<?php echo $x ?>&per-page=<?php echo $perPage; ?>" >
								<?php echo $x ?>
							</a>
						</li>
						<?php endfor; ?>
						
						<!-- Set up the Next button -->
						<li>
							<?php if($currentPage < $totalPages - 1 && $totalPages > 0): ?>
							<a href="?page=<?php echo $nextPage ?>&per-page=<?php echo $perPage ?>" aria-label="Next">
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
</div>

<?php 
mysqli_close($conn);
include 'includes/footer.php';
?>
