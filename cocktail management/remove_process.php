<?php
// Start the session
session_start();

// Check if the user's name is stored in the session
if (isset($_SESSION['name'])) {
    $name = $_SESSION['name'];
} else {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}
// Include the database connection
require('config.php');

if (isset($_POST['cocktail_id'])) {
    $cocktailId = $_POST['cocktail_id'];

    // Step 1: Retrieve the record to be deleted
    $selectQuery = "SELECT * FROM cocktail_list WHERE cocktail_id = ?";
    $stmtSelect = $conn->prepare($selectQuery);
    $stmtSelect->bind_param("i", $cocktailId);

    if ($stmtSelect->execute()) {
        $result = $stmtSelect->get_result();
        $row = $result->fetch_assoc();

        // Step 2: Insert the record into the deleted_alcohol table with the deleted timestamp
        $insertQuery = "INSERT INTO removed_cocktail (cocktail_name, cocktail_description, cost, price, profit, removed_timestamp, name)
                       VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ssddds", $row['cocktail_name'], $row['cocktail_description'], $row['cost'], $row['price'], $row['profit'], $row['name']);

        if ($stmtInsert->execute()) {
            // Step 3: Delete corresponding records from the cocktail_ingredients table
            $deleteIngredientsQuery = "DELETE FROM cocktail_ingredients WHERE cocktail_id = ?";
            $stmtDeleteIngredients = $conn->prepare($deleteIngredientsQuery);
            $stmtDeleteIngredients->bind_param("i", $cocktailId);

            if ($stmtDeleteIngredients->execute()) {
                // Step 4: Delete the record from the cocktail_list table
                $deleteListQuery = "DELETE FROM cocktail_list WHERE cocktail_id = ?";
                $stmtDeleteList = $conn->prepare($deleteListQuery);
                $stmtDeleteList->bind_param("i", $cocktailId);

                if ($stmtDeleteList->execute()) {
                    // Record deleted successfully
                    echo "success";
                } else {
                    // Error handling if deletion from cocktail_list fails
                    echo "Error deleting from cocktail_list: " . $stmtDeleteList->error;
                }

                $stmtDeleteList->close();
            } else {
                // Error handling if deletion from cocktail_ingredients fails
                echo "Error deleting from cocktail_ingredients: " . $stmtDeleteIngredients->error;
            }

            $stmtDeleteIngredients->close();
        } else {
            // Error handling if insertion into removed_cocktail table fails
            echo "Error inserting into removed_cocktail: " . $stmtInsert->error;
        }

        $stmtInsert->close();
    } else {
        // Error handling if record retrieval fails
        echo "Error retrieving the record: " . $stmtSelect->error;
    }

    $stmtSelect->close();
}
?>
