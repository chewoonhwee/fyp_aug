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
    $query = "SELECT * FROM cocktail_list WHERE cocktail_name LIKE ?";
    
    // Add wildcard (%) to allow partial matching
    $searchName = '%' . $searchName . '%';

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $searchName);
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
        function confirmDelete(cocktailId) {
            if (confirm("Are you sure you want to delete this record?")) {
                // User confirmed, proceed with the deletion
                deleteRecord(cocktailId);
            }
        }

        function deleteRecord(cocktailId) {
    // Send an AJAX request to remove_process.php
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "remove_process.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                // Check if the deletion was successful
                if (xhr.responseText.trim() === "success") {
                    // Reload the page to reflect the changes
                    location.reload();
                } else {
                    alert("Error deleting the record. Server response: " + xhr.responseText);
                }
            } else {
                alert("Error: " + xhr.status);
            }
        }
    };
    xhr.send("cocktail_id=" + cocktailId);
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
                echo "<table><tr><th>Cocktail Name</th><th>Cocktail Description</th><th>Cost</th><th>Price</th><th>Profit</th><th>Action</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row['cocktail_name']."</td>
                    <td>".$row['cocktail_description']."</td>
                    <td>RM ".$row['cost']."</td>
                    <td>".$row['price']."</td>
                    <td>".$row['profit']."</td>
                    <td><a href=\"javascript:void(0);\" onclick=\"confirmDelete(" .$row['cocktail_id']. ")\">Delete</a></td>
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
