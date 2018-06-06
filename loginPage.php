<!DOCTYPE html>
<html lang="en">


<?php 
include 'includes/header.php';
?>


<div id="wrap">
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="col-xs-10 col-xs-offset-1 col-sm-6 col-sm-offset-3 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
					<div class="account-wall">
						<center>
							<img class="" src="./images/logo.jpg" alt="Business Logo" width="250px"/>
						</center>
						<form class="form-signin" action="login.php" method="POST">
						<input type="text" name="username" class="form-control" placeholder="Username" required autofocus>
						<input type="password" name="password" class="form-control" placeholder="Password" required>
						<button class="btn btn-lg btn-primary btn-block" type="submit" value="Log in">Sign in</button>
						<a href="index.php?page=1" class="btn btn-primary btn-lg btn-block btn-block">Home Page</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>
  