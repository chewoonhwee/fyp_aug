<?php

	$datab_mysql="cocktail_inventory"; 
	
	$conn = mysqli_connect("localhost","root","") or die
		("Sorry...Could not select database.");
		
	mysqli_select_db($conn, $datab_mysql) or die
		("Sorry..You didn't select the database.");
	
?>