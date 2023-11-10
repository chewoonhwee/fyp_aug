<?php
require('top+nav.php');
// Include the database connection
require('config.php');
?>
<body>
    <header>
        <h1>Cocktail History</h1>
        <style>
        form {
        text-align: center;
        margin: 20px auto;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
        max-width: 400px;
    }

    /* Label style */
    label {
        font-weight: bold;
        display: block;
        margin-bottom: 10px;
    }

    /* Input style */
    input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    /* Search button style */
    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }
    </style>
    </header>
    <main>
    <form method="GET" action="">
            <label for="cocktail_name">Search by Cocktail Name:</label>
            <input type="text" name="cocktail_name" id="cocktail_name" required>
            <button type="submit" id="searchButton">Search</button>
        </form>


        <div id="searchResults">
            <!-- Display search results here -->
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchButton = document.getElementById('searchButton');
            const searchResults = document.getElementById('searchResults');

            searchButton.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent the form from submitting

                const searchTerm = document.getElementById('cocktail_name').value;

                // Clear previous search results
                searchResults.innerHTML = '';

                if (searchTerm.trim() !== '') {
                    // Perform an AJAX request to search_cocktail.php or use Fetch API
                    // to fetch and display search results dynamically without reloading the page.
                    // Example:
                    fetch(`search_create_cocktail_history.php?cocktail_name=${searchTerm}`)
                        .then(response => response.text())
                        .then(data => {
                            searchResults.innerHTML = data;
                        });
                }
            });
        });
    </script>
            <?php

        // Check if a search term is provided
        if (isset($_GET['cocktail_name'])) {
            $searchTerm = $_GET['cocktail_name'];

            // Query the database to search for cocktails
            $query = "SELECT * FROM cocktail_list WHERE cocktail_name LIKE ?";
            $stmt = $conn->prepare($query);
            $searchTerm = '%' . $searchTerm . '%'; // Add wildcard characters for partial matches
            $stmt->bind_param("s", $searchTerm);
            $stmt->execute();
            $result = $stmt->get_result();

            // Display search results
            if ($result->num_rows > 0) {
                echo '<h2>Search Results:</h2>';
                while ($row = $result->fetch_assoc()) {
                    echo '<p>' . $row['cocktail_name'] . '</p>';
                    // Display more information about the cocktail if needed
                }
            } else {
                echo '<p>No cocktails found with that name.</p>';
            }
        }

        // Close the database connection
        $conn->close();
        ?>

</body>
</html>