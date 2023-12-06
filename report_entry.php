<?php
// Include the database connection
require('config.php');
require('top+nav.php');
?>

    <title>Report Entry</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            /* display: flex; */
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        select, input {
            margin-bottom: 10px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .row input {
            flex: 1;
            margin-right: 10px;
        }
        /* Additional style for the container */
        #container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #rows-container{
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <?php
        // Function to fetch price by cocktail_id from the cocktail_list table
        function fetchPriceByCocktailId($cocktailId) {
            global $conn; // Assuming $conn is your database connection
    
            $query = "SELECT price FROM cocktail_list WHERE cocktail_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $cocktailId);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Assuming price is stored as a decimal, adjust accordingly
            $row = $result->fetch_assoc();
            $price = $row['price'];
    
            $stmt->close();
    
            return $price;
        }
        // Fetch cocktail list from the database
        $query = "SELECT cocktail_id, cocktail_name FROM cocktail_list";
        $result = $conn->query($query);

        $cocktails = [];
        while ($row = $result->fetch_assoc()) {
            $cocktails[] = $row;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fromDate = $_POST['from_date'];
            $toDate = $_POST['to_date'];
            

        // Insert report entries into the database
        if (isset($_POST['cocktail_id']) && isset($_POST['quantity'])) {
            $cocktailIds = $_POST['cocktail_id'];
            $quantities = $_POST['quantity'];

            // Prepare and execute the SQL statement
            $stmt = $conn->prepare("INSERT INTO report_entries (from_date, to_date, cocktail_id, cocktail_name, quantity, price) VALUES (?, ?, ?, ?, ?, ?)");

            for ($i = 0; $i < count($cocktailIds); $i++) {
                // Convert cocktail_id and quantity to integers
                $cocktailId = intval($cocktailIds[$i]);
                $quantity = intval($quantities[$i]);
                $price =fetchPriceByCocktailId($cocktailId);

                // Find the corresponding cocktail_name
                $cocktailName = '';
                foreach ($cocktails as $cocktail) {
                    if ($cocktail['cocktail_id'] == $cocktailId) {
                        $cocktailName = $cocktail['cocktail_name'];
                        break;
                    }
                }

                // Check if the value exists before trying to access it
                if (isset($fromDate, $toDate, $cocktailId, $cocktailName, $quantity)) {
                    $stmt->bind_param("ssisid", $fromDate, $toDate, $cocktailId, $cocktailName, $quantity, $price);
                    $stmt->execute();
                } else {
                    echo "Error: Some data is missing.";
                }
            }


    // Close the statement
    $stmt->close();

    // Use JavaScript for redirection
    echo '<script>window.location.href = window.location.href;</script>';
    exit;
} else {
    echo "No data received!";
}

}?>

    <form method="post" action="">
        <h2>Sales Data Entry</h2>

        <label for="from_date">From Date:</label>
        <input type="date" id="from_date" name="from_date" required >

        <label for="to_date">To Date:</label>
        <input type="date" id="to_date" name="to_date" required>

        <div id="rows-container" class="rows-container">
            <div class="row">
                <select name="cocktail_id[]" class="cocktail_id" required>
                    <option value="" disabled selected>Select Cocktail</option>
                    <?php foreach ($cocktails as $cocktail) : ?>
                        <option value="<?= $cocktail['cocktail_id'] ?>"><?= $cocktail['cocktail_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" name="quantity[]" placeholder="Quantity" class="quantity" required>
                <button type="button" class="add-ingredient">+</button>
                <button type="button" class="remove-ingredient">-</button>
            </div>
        </div>

        <input type="submit" name="submit" value="Submit" class="button">
    </form>

    <script>
    // JavaScript for adding/removing ingredient rows and validation
    document.addEventListener('DOMContentLoaded', function () {
        const rowsContainer = document.getElementById('rows-container');


        // Function to create a new row
        function createRow() {
            const newRow = document.createElement('div');
            newRow.className = 'row';

            // Clone an existing row to create the new one
            const existingRow = rowsContainer.querySelector('.row');
            newRow.innerHTML = existingRow.innerHTML;

            // Update select and input fields with unique names
            newRow.querySelector('.cocktail_id').name = 'cocktail_id[]';
            newRow.querySelector('.quantity').name = 'quantity[]';

            // Add the new row to the container
            rowsContainer.appendChild(newRow);

        }

        // Function to remove a row
        function removeRow(row) {
            row.remove();
        }

        rowsContainer.addEventListener('click', function (event) {
            if (event.target.classList.contains('add-ingredient')) {
                createRow();
            }

            if (event.target.classList.contains('remove-ingredient')) {
                removeRow(event.target.parentNode);
            }
        });
    });

    // function validateDateInput() {
    //     // Get the from and to date input elements
    //     const fromDateInput = document.getElementById('from_date');
    //     const toDateInput = document.getElementById('to_date');

    //     // Get the entered dates
    //     const fromDate = new Date(fromDateInput.value);
    //     const toDate = new Date(toDateInput.value);

    //     // Check if the difference between dates is exactly 7 days
    //     const oneWeekInMillis = 7 * 24 * 60 * 60 * 1000; // milliseconds in a week
    //     const dateDifference = toDate - fromDate;

    //     if (dateDifference !== oneWeekInMillis) {
    //         alert('Please select dates that are exactly one week apart.');
    //         // You can also reset the date inputs or handle this according to your needs
    //         fromDateInput.value = '';
    //         toDateInput.value = '';
    //     }
    // }
</script>

</body>
</html>
