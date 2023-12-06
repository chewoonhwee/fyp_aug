<?php
require('top+nav.php');
// Include the database connection
require('config.php');

// Initialize variables to store search criteria
$searchCategory = "";
$searchName = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the search criteria from the form
    $searchCategory = $_POST["search_category"];
    $searchName = $_POST["search_name"];
    
    // Perform the search query
    $query = "SELECT * FROM alcohol_inventory WHERE alcohol_category LIKE ? AND alcohol_name LIKE ?";

    
    // Add wildcard (%) to allow partial matching
    $searchCategory = '%' . $searchCategory . '%';
    $searchName = '%' . $searchName . '%';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $searchCategory, $searchName);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
    <main>
        <div class="content">
            <h2>Search Results</h2>

            <!-- Display search criteria -->
            <p>Search Criteria:</p>
            <ul>
                <li>Alcohol Category: <?php echo $searchCategory; ?></li>
                <li>Alcohol Name: <?php echo $searchName; ?></li>
            </ul>

            <!-- Display search results -->
            <?php
            if (isset($result) && $result->num_rows > 0) {
                echo "<table><tr><th>Alcohol Category</th><th>Alcohol Name</th><th>Price</th><th>Quantity</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['alcohol_category']."</td>
                    <td>".$row['alcohol_name']."</td>
                    <td>RM ".$row['price']."</td>
                    <td>".$row['quantity']."</td>
                    <td><a href=\"update_process.php?alcohol_id=" .$row['alcohol_id']. "\">Update</a></td>
                    </tr>";
                }
                echo "</table>";
            } else {
                echo "No matching cocktails found.";
            }
            ?>
        </div>
    </main>
</body>
</html>
