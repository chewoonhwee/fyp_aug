<style>
    table {
        border-collapse: collapse;
        width: 100%;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    td {
        width: 20%;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>

<?php
require('config.php'); // Include your database connection code

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["cocktail_name"])) {
    $cocktailName = $_GET["cocktail_name"];

    // Prepare and execute a query to search for cocktails
    $searchQuery = "SELECT cocktail_name, cocktail_description, cost, price, profit, creation_timestamp, name FROM cocktail_list WHERE cocktail_name LIKE ?";
    $stmt = $conn->prepare($searchQuery);
    $searchTerm = "%" . $cocktailName . "%"; // Use wildcard % for a partial match
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Cocktail Name</th><th>Cocktail Description</th><th>Cost</th><th>Price</th><th>Profit</th><th>Creation Timestamp</th><th>By: (name)</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['cocktail_name'] . "</td>";
            echo "<td>" . $row['cocktail_description'] . "</td>";
            echo "<td>RM" . $row['cost'] . "</td>";
            echo "<td>RM" . $row['price'] . "</td>";
            echo "<td>RM" . $row['profit'] . "</td>";
            echo "<td>" . $row['creation_timestamp'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        }
    } else {
        echo "No results found.";
    }
?>
