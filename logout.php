<?php 
session_start();
include 'includes/header.php';

session_unset();

$logoutOK  = session_destroy();
$statusMsg = $logoutOK ? "You have been successfully logged out" : "Something went wrong while attempting to logout. Close your browser to ensure you are logged out.";

?>

<!DOCTYPE html>
<html lang="en"> 
 
<div id="wrap">
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="col-xs-10 col-sm-8 col-md-6 col-lg-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3 col-lg-offset-3" style="margin-bottom:10px">
					<?php
					if ($logoutOK){
						echo "<ul><h3 class='text-center alert alert-success'>" . $statusMsg . "</h3></ul>";
					}
					else{
						echo "<ul><h4 class='text-center alert alert-danger'><strong>Error: </strong>" . $statusMsg . "</h4></ul>";
					}
					?>
				
					<ul>
						<a href="index.php?page=1" class="btn btn-primary btn-lg btn-huge btn-block">Home Page</a>
					</ul>
					<ul>
						<a href="loginPage.php" class="btn btn-primary btn-lg btn-huge btn-block">Log In Again</a>
					</ul>
				</div>
			</div>
			
		</div>
	</div>
</div>
<?php include 'includes/footer.php';?>