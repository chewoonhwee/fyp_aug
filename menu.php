<?php
require('top+nav.php');
require('config.php');

// Fetch menu items from the database
$query = "SELECT cocktail_id, cocktail_name, cocktail_description, price FROM menu";
$result = $conn->query($query);

$menuItems = [];
while ($row = $result->fetch_assoc()) {
    $menuItems[] = $row;
}
?>

<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add your additional styles here */
    </style>
</head>
<body>
    <div id="container">
        <h2>Menu</h2>
        <table>
            <thead>
                <tr>
                    <th>Cocktail Name</th>
                    <th>Cocktail Description</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($menuItems as $menuItem) {
                    echo "<tr>";
                    echo "<td>{$menuItem['cocktail_name']}</td>";
                    echo "<td>{$menuItem['cocktail_description']}</td>";
                    echo "<td>{$menuItem['price']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
