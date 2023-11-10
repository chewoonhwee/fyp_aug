<?php
require('top+nav.php');
// Include the database connection
require('config.php');

// Initialize variables
$cocktailName = $cocktailDescription = $cost = $price = $profit = "";
$ingredients = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve general cocktail information
    $cocktailName = $_POST["cocktail_name"];
    $cocktailDescription = $_POST["cocktail_description"];
    $cost = floatval($_POST["cocktail_cost"]);
    $price = floatval($_POST["cocktail_price"]);
    $profit = floatval($_POST["cocktail_profit"]);

    // Check if the cocktail name already exists in the database
    $checkQuery = "SELECT COUNT(*) AS count FROM cocktail_list WHERE cocktail_name = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("s", $cocktailName);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_assoc();
    $cocktailCount = $checkRow["count"];

    if ($cocktailCount > 0) {
        // Display a message to the user that the cocktail name already exists
        echo '<script>alert("Cocktail name already exists. Please check and try again.")</script>';
    } else {
        // Insert the general cocktail information into cocktail_list
        $insertCocktailQuery = "INSERT INTO cocktail_list (cocktail_name, cocktail_description, cost, price, profit, creation_timestamp, name)
                                VALUES (?, ?, ?, ?, ?, NOW(), ?)";
        $stmt = $conn->prepare($insertCocktailQuery);
        $stmt->bind_param("ssddds", $cocktailName, $cocktailDescription, $cost, $price, $profit, $name);

        if ($stmt->execute()) {
            // Get the cocktail_id of the newly inserted cocktail
            $cocktailId = $conn->insert_id;

            // Retrieve and process ingredients from the form
            $ingredientNames = $_POST["ingredient_name"];
            $amounts = $_POST["ingredient_amount"];

            // Create an array to track selected ingredients
            $selectedIngredients = array();

            // Insert ingredient information into cocktail_ingredients
            $insertIngredientQuery = "INSERT INTO cocktail_ingredients (cocktail_id, ingredient_name, amount_ml)
                                    VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertIngredientQuery);

            // Insert each ingredient into the cocktail_ingredients table
            for ($i = 0; $i < count($ingredientNames); $i++) {
                $ingredientName = $ingredientNames[$i];
                $amount = $amounts[$i];

                // Check if the same ingredient has already been selected
                if (in_array($ingredientName, $selectedIngredients)) {
                    echo '<script>alert("You cannot select the same alcohol name in different rows.")</script>';
                    exit; // Stop processing
                } else {
                    $selectedIngredients[] = $ingredientName;
                }

                $stmt->bind_param("isi", $cocktailId, $ingredientName, $amount);
                $stmt->execute();
            }
        }
    }
}
?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            width: 80%;
            max-width: 600px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        textarea,
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        select {
            width: 100%;
        }

        .add-ingredient, .remove-ingredient {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .remove-ingredient {
            background-color: #ff0000;
            margin-left: 10px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
        }

        .error-message {
            color: #ff0000;
        }
    </style>
    <title>Create Cocktail</title>
<body>
    <h1>Create a New Cocktail</h1>
    <form method="POST" action="create_cocktail.php">
        <label for="cocktail_name">Cocktail Name:</label>
        <input type="text" name="cocktail_name" id="cocktail_name" required><br>

        <label for="cocktail_description">Cocktail Description:</label>
        <textarea name="cocktail_description" id="cocktail_description" rows="4" cols="50"></textarea><br>

        <label for="cocktail_cost">Cocktail Cost (in RM):</label>
        <input type="number" step="0.01" name="cocktail_cost" id="cocktail_cost" required><br>
        
        <label for="cocktail_price">Cocktail Price (in RM):</label>
        <input type="number" step="0.01" name="cocktail_price" id="cocktail_price" required><br>

        <label for="cocktail_profit">Cocktail Profit (in RM):</label>
        <input type="number" step="0.01" name="cocktail_profit" id="cocktail_profit" required readonly>


        <h2>Ingredients:</h2>
        <div id="ingredients">
            <!-- Initial ingredient row -->
            <div class="ingredient-row">
                <label for="ingredient_name[]">Ingredient Name:</label>
                <select name="ingredient_name[]" required>
                <?php
                // Query to select ingredient names from alcohol_inventory
                $query = "SELECT DISTINCT alcohol_name FROM alcohol_inventory";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $ingredientNames[] = $row['alcohol_name'];
                    }
                }
                foreach ($ingredientNames as $name) {
                    echo "<option value='" . $name . "'>" . $name . "</option>";
                }
                ?>
                </select>

                <label for="ingredient_amount[]">Amount (in ml):</label>
                <input type="number" step="0.01" name="ingredient_amount[]" id="amount_ml" required>

                <button type="button" class="add-ingredient">+</button>
                <button type="button" class="remove-ingredient">-</button>
            </div>
        </div>

        <button type="submit">Save</button>
    </form>

    <script>
        // JavaScript for adding/removing ingredient rows and validation
        document.addEventListener('DOMContentLoaded', function () {
                const addIngredientButtons = document.querySelectorAll('.add-ingredient');
                const ingredientsContainer = document.getElementById('ingredients');

                // Function to create a new ingredient row
                function createIngredientRow() {
                    const newRow = document.createElement('div');
                    newRow.className = 'ingredient-row';

                    // Clone an existing ingredient row to create the new one
                    const existingRow = ingredientsContainer.querySelector('.ingredient-row');
                    newRow.innerHTML = existingRow.innerHTML;

                    // Add the new row to the container
                    ingredientsContainer.appendChild(newRow);
                }

                // Function to remove an ingredient row
                function removeIngredientRow(row) {
                    row.remove();
                }

                for (const addButton of addIngredientButtons) {
                    addButton.addEventListener('click', function () {
                        createIngredientRow();
                    });
                }

                ingredientsContainer.addEventListener('click', function (event) {
                    if (event.target && event.target.className == 'remove-ingredient') {
                        removeIngredientRow(event.target.parentNode);
                    }
                });

                // Function to calculate and display the profit
                function calculateProfit() {
                    const costInput = document.getElementById('cocktail_cost');
                    const priceInput = document.getElementById('cocktail_price');
                    const profitInput = document.getElementById('cocktail_profit');

                    const cost = parseFloat(costInput.value);
                    const price = parseFloat(priceInput.value);

                    if (!isNaN(cost) && !isNaN(price)) {
                        const profit = price - cost;
                        profitInput.value = profit.toFixed(2); // Display profit with two decimal places
                    } else {
                        profitInput.value = ''; // Clear the profit field if either cost or price is not a valid number
                    }
                }

                // Attach the calculateProfit function to the change event of cost and price inputs
                const costInput = document.getElementById('cocktail_cost');
                const priceInput = document.getElementById('cocktail_price');
                costInput.addEventListener('change', calculateProfit);
                priceInput.addEventListener('change', calculateProfit);
            });
            // Function to validate ingredient selections
                function validateIngredients() {
                    const selectElements = document.querySelectorAll('select[name^="ingredient_name"]');
                    const selectedIngredients = new Set();

                    for (const select of selectElements) {
                        const ingredientName = select.value;

                        if (selectedIngredients.has(ingredientName) && ingredientName !== "") {
                            alert('You cannot select the same ingredient name.');
                            return false;
                        }

                        selectedIngredients.add(ingredientName);
                    }

                    return true;
                }

                // Function to handle form submission
                function handleSubmit(event) {
                    if (!validateIngredients()) {
                        event.preventDefault(); // Prevent form submission
                    } else {
                        // Display a success message
                        alert('Form submitted successfully. Your cocktail has been added.');
                        
                        // Optionally, you can reset the form fields or redirect the user to another page.
                        // Example: form.reset(); or window.location.href = 'success.php';
                    }
                }

                // Attach the handleSubmit function to the form's submit event
                const form = document.querySelector('form');
                form.addEventListener('submit', handleSubmit);

    </script>

    </body>
</html>

