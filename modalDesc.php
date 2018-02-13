<?php
include 'functions/general.php';

sleep(1);

// Connect to the database
$conn = db_connector();

//--------------insert data--------------------
$productID  = $_POST['id'];

$query = "SELECT * FROM products WHERE productID = $productID";

?>

<div>
	<?php 
		if ($productRecord = $conn->query($query)){
			while($product = $productRecord->fetch_assoc()){
				echo "<center><img class='img-responsive' src='".$product['imagePath']."'/>";
				echo "<h3>".$product['title']."</h3>"; 
				echo "<hr />";
				echo "<p><span class='label label-warning'>Product ID: ".$product['productID']."</span></p>";
				echo "<p><span class='label label-success'>Price: $".$product['price']."</span></p>";
				echo "<p>".$product['shortDesc']."</p>"; 
				echo "<hr />";
				echo $product['longDesc'];
				echo "</center>";
			}//end while
		}
		//$newsRecord->free();
		mysqli_close($conn); // close database connection
	?>
</div>