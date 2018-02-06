<!DOCTYPE html>
<html lang="en">

<?php 
include 'includes/head.php';
include 'includes/header.php';
?>

<div id="wrap">
	<div id="main">
		<div class="container">
			<div class="row">
				<div class="col-sm-6 col-md-4 col-md-offset-4">
					<div class="account-wall">
						<center><img class="" src="./images/logo.jpg"
							alt="" width="250px"/></center>
						<form class="form-signin" action="login.php" method="POST">
						<input type="text" name="username" class="form-control" placeholder="Login" required autofocus>
						<input type="password" name="password" class="form-control" placeholder="Password" required>
						<button class="btn btn-lg btn-primary btn-block" type="submit" value="Log in">Sign in</button>
						<a href="index.php?page=1" class="btn btn-primary btn-lg btn-block btn-block">Home Page</a>
						<!--<label class="checkbox pull-left"> 
							<input type="checkbox" value="remember-me">
							Remember me
						</label>-->
						<!--<a href="#" class="pull-right need-help">Need help? </a><span class="clearfix"></span>-->
						</form>
					</div>
					<!--<a href="register.php" class="text-center new-account">Create an account </a>-->
				</div>
			</div>
		</div>
	</div>
</div>

<?php include 'includes/footer.php';?>
  