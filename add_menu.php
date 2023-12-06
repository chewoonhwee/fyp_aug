<?php
// Include the necessary files and initialize the session if not already done
require('top+nav.php');
require('config.php');

// Fetch cocktail list from the database
$query = "SELECT cocktail_id, cocktail_name FROM cocktail_list";
$result = $conn->query($query);

$cocktails = [];
while ($row = $result->fetch_assoc()) {
    $cocktails[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    if (isset($_POST['addCocktail'])) {
        $selectedCocktailId = $_POST['cocktail'];
        
        // Fetch details of the selected cocktail
        $queryCocktail = "SELECT cocktail_name, cocktail_description, price FROM cocktail_list WHERE cocktail_id = $selectedCocktailId";
        $resultCocktail = $conn->query($queryCocktail);
        $selectedCocktail = $resultCocktail->fetch_assoc();

        // Store the selected cocktail in a session variable or database table for later use
        // You can modify this part based on your project structure
        $_SESSION['selectedCocktails'][] = [
            'cocktail_id' => $selectedCocktailId,
            'cocktail_name' => $selectedCocktail['cocktail_name'],
            'cocktail_description' => $selectedCocktail['cocktail_description'],
            'price' => $selectedCocktail['price']
        ];
    }

        // Check if the delete button is clicked
        if (isset($_POST['deleteButton']) && isset($_POST['deleteCocktail'])) {
            $deleteIndex = $_POST['deleteCocktail'];
            
            // Remove the selected cocktail from the session
            if (isset($_SESSION['selectedCocktails'][$deleteIndex])) {
                unset($_SESSION['selectedCocktails'][$deleteIndex]);
                // Optional: reindex the array to avoid missing keys
                $_SESSION['selectedCocktails'] = array_values($_SESSION['selectedCocktails']);
            }
        }
    
        // Check if the form is submitted
        elseif (isset($_POST['addCocktail'])) {
            // Your existing code for adding a cocktail
        }   

        if (isset($_POST['saveButton'])) {
            // Save button clicked, handle saving the displayed table data to the database
            if (!empty($_SESSION['selectedCocktails'])) {
                foreach ($_SESSION['selectedCocktails'] as $selectedCocktail) {
                    $cocktailId = $selectedCocktail['cocktail_id'];
                    $cocktailName = $conn->real_escape_string($selectedCocktail['cocktail_name']);
                    $cocktailDescription = $conn->real_escape_string($selectedCocktail['cocktail_description']);
                    $price = $selectedCocktail['price'];
    
                    // Use prepared statements to prevent SQL injection
                    $insertQuery = "INSERT INTO menu (cocktail_id, cocktail_name, cocktail_description, price) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($insertQuery);
                    $stmt->bind_param("isss", $cocktailId, $cocktailName, $cocktailDescription, $price);
                    $stmt->execute();
                    $stmt->close();
                }
    
                // Clear the selectedCocktails session variable after saving to the database
                $_SESSION['selectedCocktails'] = [];
            }
        }
}
?>

<!DOCTYPE HTML>
<html>
<head>
    <title>Menu</title>
    <link rel="stylesheet" href="style.css">
    <!-- Additional styles if needed -->
    <style>
        /* Add your additional styles here */
    </style>
</head>
<body>
    <div id="container">
        <h2>Menu</h2>
        <form method="post" action="">
            <label for="cocktail">Select Cocktail:</label>
            <select name="cocktail" id="cocktail" required>
                <option value="" disabled selected>Select Cocktail</option>
                <?php foreach ($cocktails as $cocktail) : ?>
                    <option value="<?= $cocktail['cocktail_id'] ?>"><?= $cocktail['cocktail_name'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="addCocktail">Add</button>
        </form>

        <h3>Selected Cocktails</h3>
        <form method="post" action="">
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
                    if (!empty($_SESSION['selectedCocktails'])) {
                        foreach ($_SESSION['selectedCocktails'] as $key => $selectedCocktail) {
                            echo "<tr>";
                            echo "<td>{$selectedCocktail['cocktail_name']}</td>";
                            echo "<td>{$selectedCocktail['cocktail_description']}</td>";
                            echo "<td>{$selectedCocktail['price']}</td>";
                            echo "<td><form method='post' action=''>
                                    <input type='hidden' name='deleteCocktail' value='$key'>
                                    <button type='submit' name='deleteButton'>Delete</button>
                                </form></td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" name="saveButton">Save</button>
        </form>
    </div>
</body>
</html>