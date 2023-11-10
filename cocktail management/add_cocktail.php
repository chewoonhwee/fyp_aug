
<?php
require('top+nav.php');
// Include the database connection
require('config.php');
?>
   <style>
    form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}

label {
    display: block;
    margin-bottom: 10px;
}

input[type="text"],
input[type="number"] {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

input[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}
/* Style for the dropdown menu */
.dropdown {
    position: relative;
    display: inline-block;
}

/* Style for the "Add Cocktail" button */
.dropbtn {
    color: white;
    text-decoration: none;
    padding: 14px 16px;
    border: none;
    background-color: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
}

/* Style for the dropdown content (hidden by default) */
.dropdown-content {
    display: none;
    position: absolute;
    background-color: #333;
    min-width: 160px;
    z-index: 1;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    right: 0;
}

/* Style for dropdown links */
.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color on hover */
.dropdown-content a:hover {
    background-color: #ddd;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
}

</style>
</head>
<body>
    <main>
        <div class="content">
        <?php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user inputs
    $alcoholCategory = $_POST["alcohol_category"];
    $alcoholName = $_POST["alcohol_name"];
    $price = $_POST["price"];
    $quantity = $_POST["quantity"];

    // Validate user inputs (e.g., check for empty fields, validate numerical inputs, sanitize data)
    if (empty($alcoholCategory) || empty($alcoholName) || empty($price) || empty($quantity)) {
        echo '<script>alert("Please fill in all fields.");</script>';
    } else {
        // Check if the data already exists in the database
        $checkQuery = "SELECT * FROM alcohol_inventory WHERE alcohol_category = ? AND alcohol_name = ?";
        $checkStmt = $conn->prepare($checkQuery);
        $checkStmt->bind_param("ss", $alcoholCategory, $alcoholName);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Data already exists in the database
            echo '<script>alert("Error: This combination of Alcohol Category and Alcohol Name already exists in the inventory.");</script>';
        } else {
            // Insert data into the database
            $insertQuery = "INSERT INTO alcohol_inventory (alcohol_category, alcohol_name, price, quantity, creation_timestamp, name) VALUES (?, ?, ?, ?, NOW(), ?)";
            $stmt = $conn->prepare($insertQuery);

            if ($stmt) {
                $stmt->bind_param("ssdds", $alcoholCategory, $alcoholName, $price, $quantity, $name);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Data inserted successfully
                    echo '<script>alert("Cocktail added to the inventory.");</script>';
                } else {
                    // Error handling if data insertion fails
                    echo '<script>alert("Error adding the cocktail to the inventory: ' . $stmt->error . '");</script>';
                }

                $stmt->close();
            } else {
                // Error handling for query preparation
                echo '<script>alert("Error preparing the SQL statement: ' . $conn->error . '");</script>';
            }
        }
        $checkStmt->close();
    }
}
?>

            <h2>Add Alcohol</h2>
                <form method="POST" action="add_cocktail.php">
                    <label for="alcohol_category">Alcohol Category:</label>
                    <input type="text" name="alcohol_category" id="alcohol_category" required>

                    <label for="alcohol_name">Alcohol Name:</label>
                    <input type="text" name="alcohol_name" id="alcohol_name" required>

                    <label for="price">Price:</label>
                    <input type="number" name="price" id="price" step="0.01" required>

                    <label for="quantity">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" required>

                    <input type="submit" value="Add">
                </form>
                
            <script>
                function validateForm() {
                    var alcoholCategory = document.getElementById("alcohol_category").value;
                    var alcoholName = document.getElementById("alcohol_name").value;
                    var price = document.getElementById("price").value;
                    var quantity = document.getElementById("quantity").value;

                    if (alcoholCategory === "" || alcoholName === "" || price === "" || quantity === "") {
                        alert("Please fill in all fields.");
                        return false; // Prevent form submission
                    }

                    return true; // Allow form submission
                }
            </script>
        </div>
    </main>
</body>
</html>