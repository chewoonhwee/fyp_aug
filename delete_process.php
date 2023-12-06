<?php
require('config.php');

if (isset($_POST['alcohol_id'])) {
    $alcoholId = $_POST['alcohol_id'];

    // Step 1: Retrieve the record to be deleted
    $selectQuery = "SELECT * FROM alcohol_inventory WHERE alcohol_id = ?";
    $stmtSelect = $conn->prepare($selectQuery);
    $stmtSelect->bind_param("i", $alcoholId);

    if ($stmtSelect->execute()) {
        $result = $stmtSelect->get_result();
        $row = $result->fetch_assoc();

        // Step 2: Insert the record into the deleted_alcohol table with the deleted timestamp
        $insertQuery = "INSERT INTO deleted_alcohol (alcohol_category, alcohol_name, price, quantity, deleted_timestamp, name)
                       VALUES (?, ?, ?, ?, NOW(), ?)";
        $stmtInsert = $conn->prepare($insertQuery);
        $stmtInsert->bind_param("ssdis", $row['alcohol_category'], $row['alcohol_name'], $row['price'], $row['quantity'], $row['name']);

        if ($stmtInsert->execute()) {
            // Record inserted into deleted_alcohol table

            // Step 3: Delete the record from the alcohol_inventory table
            $deleteQuery = "DELETE FROM alcohol_inventory WHERE alcohol_id = ?";
            $stmtDelete = $conn->prepare($deleteQuery);
            $stmtDelete->bind_param("i", $alcoholId);

            if ($stmtDelete->execute()) {
                // Record deleted successfully
                echo "success";
            } else {
                // Error handling if deletion fails
                echo "Error deleting the record: " . $stmtDelete->error;
            }

            $stmtDelete->close();
        } else {
            // Error handling if insertion into deleted_alcohol table fails
            echo "Error inserting into deleted_alcohol: " . $stmtInsert->error;
        }

        $stmtInsert->close();
    } else {
        // Error handling if record retrieval fails
        echo "Error retrieving the record: " . $stmtSelect->error;
    }

    $stmtSelect->close();
}
?>
