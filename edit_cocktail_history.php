<?php
require('top+nav.php');
// Include the database connection
require('config.php');
?>
<body>
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

<div class="content">
        <h2>Edit Cocktail History</h2>
                <!-- Search bar form -->
                <form method="POST" action="edit_cocktail_history.php">
                    <div class="form-group">
                        <div>
                            <label for="search_cocktail_name">Cocktail Name:</label>
                            <input type="text" name="search_cocktail_name" id="search_cocktail_name">
                        </div>
                    </div>
                         <input type="submit" name="submit" value="Submit" class="button">
                </form>
        <table>
            <tr>
                <th>Cocktail Name</th>
                <th>Cocktail Description</th>
                <th>Cost</th>
                <th>Price</th>
                <th>Profit</th>
                <th>Creation Timestamp</th>
                <th>By (name)</th>
            </tr>
            <?php

            // Initialize variables to store search criteria
            $searchAlcoholName = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Retrieve the search criteria from the form

    $searchCocktailName = $_POST["search_cocktail_name"];

    // Perform the search query
    $history_query = "SELECT * FROM edit_history WHERE
         cocktail_name LIKE '%$searchCocktailName%'";
    
    // Execute the query and display the results
    $history_result = mysqli_query($conn, $history_query);

    if ($history_result) {
        // Display the search results in a table, similar to your search_results.php
        // ...
        while ($row = mysqli_fetch_assoc($history_result)) {
            echo "<tr>";
            echo "<td>" . $row['cocktail_name'] . "</td>";
            echo "<td>" . $row['cocktail_description'] . "</td>";
            echo "<td>" . $row['cost'] . "</td>";
            echo "<td>" . $row['price'] . "</td>";
            echo "<td>" . $row['profit'] . "</td>";
            echo "<td>" . $row['creation_timestamp'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "No matching records found.";
    }
}
            ?>
        </table>
    </div>
</body>
</html>