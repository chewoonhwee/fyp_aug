<?php
require('top+nav.php');
// Include the database connection
require('config.php');

// Initialize variables to store search criteria
$searchName = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the search criteria from the form
    $searchName = $_POST["search_name"];
    
    // Perform the search query
    $query = "SELECT cl.*, ci.ingredient_name, ci.amount_ml
              FROM cocktail_list cl
              LEFT JOIN cocktail_ingredients ci ON cl.cocktail_id = ci.cocktail_id
              WHERE cl.cocktail_name LIKE ?";

    // Add wildcard (%) to allow partial matching
    $searchName = '%' . $searchName . '%';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $searchName);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<style>
/* Add this CSS to your styles.css or in a style tag in your HTML file */

.results-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.results-table th, .results-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.results-table th {
    background-color: #f2f2f2;
}

/* Add alternating background color for better readability */
.results-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Style for the outline based on cocktail_id */
.results-table tr.outline {
    border-top: 2px solid #333;
    border-bottom: 2px solid #333;
}

/* Style for additional rows within the same cocktail_id */
.results-table tr.additional-rows td {
    border-top: 1px solid #ddd;
    border-bottom: 1px solid #ddd;
}

/* Add hover effect on rows for better interactivity */
.results-table tr:hover {
    background-color: #e2e2e2;
}</style>
    <main>
    <div class="content">
            <h2>Search Results</h2>

            <!-- Display search criteria -->
            <p>Search Criteria:</p>
            <ul>
                <li>Cocktail Name: <?php echo $searchName; ?></li>
            </ul>

            <!-- Display search results -->
            <?php
            if (isset($result) && $result->num_rows > 0) {
                echo "<table class='results-table'>";
                echo "<tr><th>Cocktail Name</th><th>Cocktail Description</th><th>Cost</th><th>Price</th><th>Profit</th><th>Ingredient Name</th><th>Amount (ml)</th><th>Action</th></tr>";

                $currentCocktailId = null; // To keep track of the current cocktail_id

                while ($row = $result->fetch_assoc()) {
                    // Check if it's a new cocktail_id
                    if ($row['cocktail_id'] != $currentCocktailId) {
                        // Display the information for the new cocktail
                        echo "<tr>";
                        echo "<td>" . $row['cocktail_name'] . "</td>";
                        echo "<td>" . $row['cocktail_description'] . "</td>";
                        echo "<td>RM " . $row['cost'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['profit'] . "</td>";
                        echo "<td>" . $row['ingredient_name'] . "</td>";
                        echo "<td>" . $row['amount_ml'] . "</td>";
                        echo "<td><a href=\"edit_process.php?cocktail_id=" . $row['cocktail_id'] . "\">Update</a></td>";
                        echo "</tr>";

                        $currentCocktailId = $row['cocktail_id'];
                    } else {
                        // Display additional rows for the same cocktail_id
                        echo "<tr>";
                        echo "<td></td><td></td><td></td><td></td><td></td>";
                        echo "<td>" . $row['ingredient_name'] . "</td>";
                        echo "<td>" . $row['amount_ml'] . "</td>";
                        echo "<td></td>";
                        echo "</tr>";
                    }
                }

                echo "</table>";
            } else {
                echo "<p>No matching cocktails found.</p>";
            }
            ?>
        </div>
    </main>
</body>
</html>
