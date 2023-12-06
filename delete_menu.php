<?php
require('top+nav.php');
require('config.php');

// Function to handle deleting a menu item
function deleteMenuItem($conn, $cocktailId) {
    // Use prepared statements to prevent SQL injection
    $deleteQuery = "DELETE FROM menu WHERE cocktail_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $cocktailId);
    $stmt->execute();
    $stmt->close();
}

// Check if the delete button is clicked
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteButton']) && isset($_POST['deleteCocktail'])) {
    $deleteCocktailId = $_POST['deleteCocktail'];
    deleteMenuItem($conn, $deleteCocktailId);
}

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
    <title>Manage Menu</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Add your additional styles here */
    </style>
</head>
<body>
    <div id="container">
        <h2>Manage Menu</h2>
        <table>
            <thead>
                <tr>
                    <th>Cocktail Name</th>
                    <th>Cocktail Description</th>
                    <th>Price</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($menuItems as $menuItem) {
                    echo "<tr>";
                    echo "<td>{$menuItem['cocktail_name']}</td>";
                    echo "<td>{$menuItem['cocktail_description']}</td>";
                    echo "<td>{$menuItem['price']}</td>";
                    echo "<td>
                            <form method='post' action=''>
                                <input type='hidden' name='deleteCocktail' value='{$menuItem['cocktail_id']}'>
                                <button type='submit' name='deleteButton'>Delete</button>
                            </form>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
