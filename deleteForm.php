<?php
session_start();
include 'includes/functions.php';
//include 'deleteConfirm.php';

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
<?php 
include 'includes/header.php';
?>

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
	
	
	<!-- Display products -->
	<div id="main">
		<div class="container">
			
			<h2>Delete Item</h2>
			
			<div class="row">
				<div class="col-xs-12" style="margin-bottom:10px">
					
					<div class="table-responsive">
						<table class="table table-striped">
							<?php foreach($results as $result): ?>
								<div class="result">
									<form class="form-inline" action="deleteItem.php" method="GET" enctype="multipart/form-data" id="deleteItem"/>
										<tr>
											<?php
											echo "<td><a href=/deleteItem.php?productID=" . $result['productID'] . '>' . $result['title'] .  "</a></td>";
											echo "<td><a class='btn btn-small btn-danger pull-right' href='deleteConfirm.php?del=$result[productID]'>Delete</a></td>";
											?>
										</tr>
									</form>
								</div>
							<?php endforeach; ?>
						</table>
					</div>
					
				</div>
			</div>
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

<?php include 'includes/footer.php';?>