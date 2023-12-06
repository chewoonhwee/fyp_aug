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
</style>
    <script>
        function confirmDelete(alcoholId) {
            if (confirm("Are you sure you want to delete this record?")) {
                // User confirmed, proceed with the deletion
                deleteRecord(alcoholId);
            }
        }

        function deleteRecord(alcoholId) {
            // Send an AJAX request to delete_process.php
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_process.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Check if the deletion was successful
                    if (xhr.responseText == "success") {
                        // Reload the page to reflect the changes
                        location.reload();
                    } else {
                        alert("Error deleting the record.");
                    }
                }
            };
            xhr.send("alcohol_id=" + alcoholId);
        }
    </script>
</head>
<body>
    <main>
        <div class="content">
            <h2>Search Results</h2>

            <!-- Display search results -->
            <?php
            if (isset($result) && $result->num_rows > 0) {
                echo "<table><tr><th>Alcohol Category</th><th>Alcohol Name</th><th>Price</th><th>Quantity</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['alcohol_category']."</td>
                    <td>".$row['alcohol_name']."</td>
                    <td>RM ".$row['price']."</td>
                    <td>".$row['quantity']."</td>
                    <td><a href=\"javascript:void(0);\" onclick=\"confirmDelete(" .$row['alcohol_id']. ")\">Delete</a></td>
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
