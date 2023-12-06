<?php
// Include the database connection
require('config.php');

// Fetch cocktail names and prices from the database
$query = "SELECT cocktail_name, price FROM cocktail_list";
$result = $conn->query($query);

$cocktailData = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cocktailData[] = array(
            'name' => $row['cocktail_name'],
            'price' => $row['price']
        );
    }
}

// Close the database connection
$conn->close();

// Encode the data as JSON and echo it
// echo json_encode($cocktailData);
?>
