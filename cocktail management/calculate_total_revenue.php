<?php
// Include the database connection
require('config.php');

// Assuming you have received the start and end dates from the URL parameters
$startDate = $_GET['start_date'];
$endDate = $_GET['end_date'];

// Fetch data from the database based on the selected date range
$query = "SELECT r.cocktail_name, r.quantity, c.price FROM report_entries r JOIN cocktail_list c ON r.cocktail_name = c.cocktail_name WHERE r.from_date >= '$startDate' AND r.to_date <= '$endDate'";
$result = $conn->query($query);

// Calculate total revenue
$totalRevenue = 0;
while ($row = $result->fetch_assoc()) {
    $totalRevenue += $row['quantity'] * $row['price'];
}

// Close the database connection
$conn->close();

// Return the total revenue as JSON
echo json_encode(['totalRevenue' => $totalRevenue], JSON_NUMERIC_CHECK);
?>
