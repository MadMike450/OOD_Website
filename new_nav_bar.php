<!-- NEW header logo, header buttons, and session -->
<nav class="navbar navbar-default">
	<div class="container">
		
		<div class="navbar-header">
			<a class="navbar-brand" href="./index.php?page=1">
				<img class="img-responsive" alt="Brand" src="./images/logo.jpg" width="100px">
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