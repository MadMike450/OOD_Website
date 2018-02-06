<?php
//-------------connect to the database-------------
$servername = 'mysql.objectsofdesirefindlay.com';
$user       = 'jasrhu2';
$password   = 'QRcodes21';
$dbname     = 'qrusers';

$conn = new mysqli($servername, $user, $password, $dbname) or die("Unable to connect to the database");
	

$page 		=	isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage	= 	isset($_GET['per-page']) && $_GET['per-page'] <= 10 ? (int)$_GET['per-page'] : 9;

//Positioning
$start = ($page > 1) ? ($page * $perPage) - $perPage : 0;

//Query


$sql = "SELECT  SQL_CALC_FOUND_ROWS *
		FROM	products
		ORDER BY title
		LIMIT	{$start}, {$perPage}";


$result = $conn->query($sql);
$total = $conn->query("SELECT FOUND_ROWS() as total")->fetch_assoc()['total'];

$pages = ceil($total/$perPage);

?>

<!DOCTYPE html>
<html lang="en">	
<?php
include 'includes/head.php';
include 'includes/header.php';

?>


<div id="wrap">
	<!-- header logo and buttons -->
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand" href="index.php">
					<img class="img-responsive" alt="Brand" src="./images/logo.jpg" width="100px">
				</a>
			</div>
			<a class='btn btn-small btn-default pull-right' href='loginPage.php'>Log In</a>
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
			
			<!-- product info form -->
			<div class="row">
				<!--<div class="col-xs-12" style="margin-bottom:10px">-->
					
					<?php foreach($result as $results): ?>
					<div class="result">
						<form class="form-inline" action="displayItem.php" method="GET" enctype="multipart/form-data" id="displayItem"/>
							<!--<div class="table">-->
							<div class="col-xs-12 col-sm-6 col-md-4">
								<div class="row">
									<div class="col-sm-12">
										<?php echo "<td><center><img style='margin-top:20px' class='img-responsive' alt='Brand' style='max-height: 150px' src='" . $results['imagePath'] . "'></center></td>";?>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-12">
										<?php echo "<td><center><a href=/displayItem.php?productID=" . $results['productID'] . '>' . $results['title'] .  "</a></center></td>";?>
									</div>	
								</div>
							</div>
						</form>
					</div>
					<?php endforeach; ?>
				</div>
			</div>	
		
	<!----pagination page navigation bar--->
	<nav>
	<div class="text-center">
 <ul class="pagination">
  <li>
	<!---Functionality to navigate pages--->
	<?php
	$firstPage     = 1;
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
	//$homepage    = 'href="index.php"';
	//if($page=="active")
	
	$currentPage = (int)$_GET['page'];
	$nextPage    = $page + 1;
	
	if($currentPage < $x - 1 || $currentPage == $firstPage ): ?>
	
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