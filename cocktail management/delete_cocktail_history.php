<?php
require('top+nav.php');
// Include the database connection
require('config.php');
?>
    <style>
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
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
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

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        h1 {
            margin: 0;
        }

        .nav-links {
            list-style-type: none;
            padding: 0;
            text-align: center;
        }

        .nav-links li {
            display: inline;
            margin: 0 10px;
        }

        .nav-links a {
            text-decoration: none;
            color: #007BFF;
        }

        .content {
            background-color: white;
            margin: 20px auto;
            padding: 20px;
            max-width: 800px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        form {
            background-color: #f9f9f9;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 20px;
        }

        label, input[type="text"] {
            display: block;
            margin: 10px 0;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    <div class="content">
        <h2>Alcohol Deletion History</h2>
        <!-- Search bar form -->
        <form method="POST" action="delete_cocktail_history.php">
            <div class="form-group">
                <div>
                    <label for="search_category">Alcohol Category:</label>
                    <input type="text" name="search_alcohol_category" id="search_alcohol_category">
                </div>
                <div>
                    <label for="search_alcohol_name">Alcohol Name:</label>
                    <input type="text" name="search_alcohol_name" id="search_alcohol_name">
                </div>
                <div>
                    <label for="search_deleted_timestamp">Deleted Timestamp:</label>
                    <input type="text" name="search_deleted_timestamp" id="search_deleted_timestamp">
                </div>
                <div>
                    <label for="search_name">Deleted By (name):</label>
                    <input type="text" name="search_name" id="search_name">
                </div>
            </div>
            <input type="submit" name="submit" value="Submit" class="button">
        </form>
<!-- PHP code for displaying deletion history goes here -->
<?php
// Initialize variables to store search criteria
$searchCategory = "";
$searchAlcoholName = "";
$searchDeletedTimestamp = "";
$searchUser = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchCategory = "%" . $_POST["search_alcohol_category"] . "%";
    $searchAlcoholName = "%" . $_POST["search_alcohol_name"] . "%";
    $searchDeletedTimestamp = "%" . $_POST["search_deleted_timestamp"] . "%";
    $searchUser = "%" . $_POST["search_name"] . "%";
}

// Query to select the desired columns from the delete_history table
$query = "SELECT * FROM deleted_alcohol WHERE
    alcohol_category LIKE ?
    AND alcohol_name LIKE ?
    AND deleted_timestamp LIKE ?
    AND name LIKE ?
ORDER BY deleted_timestamp DESC";  // Add the ORDER BY clause

$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $searchCategory, $searchAlcoholName, $searchDeletedTimestamp, $searchUser);

if ($stmt->execute()) {
    $result = $stmt->get_result();

    // Check if there are rows returned
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Alcohol Category</th><th>Alcohol Name</th><th>Price</th><th>Quantity</th><th>Deleted Timestamp</th><th>By (name)</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['alcohol_category'] . "</td>";
            echo "<td>" . $row['alcohol_name'] . "</td>";
            echo "<td>RM " . $row['price'] . "</td>";
            echo "<td>" . $row['quantity'] . "</td>";
            echo "<td>" . $row['deleted_timestamp'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "No records found.";
    }
} else {
    // Error handling if execution fails
    die("Execute failed: " . $stmt->error);
}

// Close the connection
mysqli_close($conn);
?>
</body>
</html>
