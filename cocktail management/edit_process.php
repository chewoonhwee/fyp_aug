
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
            background-color: #f2f2f2;
        }
        header {
            
            color: white;
            text-align: center;
            padding: 10px;
        }
        nav {
            background-color: #333;
            padding: 5px;
        }
        .nav-links {
            list-style: none;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .nav-links li {
            margin: 0 15px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        .content {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            margin: 20px;
        }
        .txtField {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        .form-container {
            max-width: 400px; /* Set your desired maximum width */
            margin: 0 auto; /* Center the container horizontally */
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
</style>
</head>
<body>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Cocktail</title>
    <style>
        /* Add your CSS styles here */
    </style>
</head>
<body>

<?php
// Fetch data based on cocktail ID
$result = mysqli_query($conn, "SELECT * FROM cocktail_list WHERE cocktail_id='" . $_GET['cocktail_id'] . "'");
$row = mysqli_fetch_array($result);

// Check if data is fetched successfully
if (!$row) {
    // Handle the case where data is not found
    echo "Error: Cocktail not found.";
    exit;
}

if (count($_POST) > 0) {
    $cocktail_id = $_POST['cocktail_id'];

    // Insert original data into edit_history
    $originalListQuery = "INSERT INTO edit_history (cocktail_id, cocktail_name, cocktail_description, cost, price, profit, creation_timestamp, name)
                         SELECT cocktail_id, cocktail_name, cocktail_description, cost, price, profit,creation_timestamp, name
                         FROM cocktail_list
                         WHERE cocktail_id = ?";
    $stmtOriginalList = $conn->prepare($originalListQuery);
    $stmtOriginalList->bind_param("i", $cocktail_id);
    $stmtOriginalList->execute();

    // Insert original data into original_cocktail_ingredients
    $originalIngredientsQuery = "INSERT INTO edit_cocktail_ingredients_history (cocktail_ingredient_id, cocktail_id, ingredient_name, amount_ml)
                                 SELECT cocktail_ingredient_id, cocktail_id, ingredient_name, amount_ml
                                 FROM cocktail_ingredients
                                 WHERE cocktail_id = ?";
    $stmtOriginalIngredients = $conn->prepare($originalIngredientsQuery);
    $stmtOriginalIngredients->bind_param("i", $cocktail_id);
    $stmtOriginalIngredients->execute();

    // Update data in cocktail_list
    $updateListQuery = "UPDATE cocktail_list
                        SET cocktail_name = ?, cocktail_description = ?, cost = ?, price = ?, profit = ?
                        WHERE cocktail_id = ?";
    $stmtUpdateList = $conn->prepare($updateListQuery);
    $stmtUpdateList->bind_param("ssdddi", $_POST['cocktail_name'], $_POST['cocktail_description'], $_POST['cocktail_cost'], $_POST['cocktail_price'], $_POST['cocktail_profit'], $cocktail_id);
    $stmtUpdateList->execute();

    // Delete existing ingredients for the current cocktail ID
    $deleteIngredientsQuery = "DELETE FROM cocktail_ingredients WHERE cocktail_id = ?";
    $stmtDeleteIngredients = $conn->prepare($deleteIngredientsQuery);
    $stmtDeleteIngredients->bind_param("i", $cocktail_id);
    $stmtDeleteIngredients->execute();

    // Loop through ingredient arrays
    for ($i = 0; $i < count($_POST['ingredient_name']); $i++) {
        $ingredientName = $_POST['ingredient_name'][$i];
        $amount_ml = $_POST['amount_ml'][$i];

        // Insert data into cocktail_ingredients
        $insertIngredientsQuery = "INSERT INTO cocktail_ingredients (cocktail_id, ingredient_name, amount_ml)
                                VALUES (?, ?, ?)";
        $stmtInsertIngredients = $conn->prepare($insertIngredientsQuery);
        $stmtInsertIngredients->bind_param("isd", $cocktail_id, $ingredientName, $amount_ml);
        $stmtInsertIngredients->execute();
    }


    echo '<script>alert("Record Modified Successfully");</script>';
    echo '<script>window.location.href = "home.php";</script>';
}
?>

<div class="content">
    <div class="form-container">
        <h2>Edit Cocktail</h2>
        <form name="cocktail_details" method="post" action="">
    <label for="cocktail_name">Cocktail Name:</label>
    <input type="hidden" name="cocktail_id" value="<?php echo $row['cocktail_id']; ?>">
    <input type="text" name="cocktail_name" id="cocktail_name" value="<?php echo $row['cocktail_name']; ?>"><br>

    <label for="cocktail_description">Cocktail Description:</label>
    <textarea name="cocktail_description" id="cocktail_description" rows="4" cols="50"><?php echo $row['cocktail_description']; ?></textarea><br>

    <label for="cocktail_cost">Cocktail Cost (in RM):</label>
    <input type="number" step="0.01" name="cocktail_cost" id="cocktail_cost" value="<?php echo $row['cost']; ?>"><br>

    <label for="cocktail_price">Cocktail Price (in RM):</label>
    <input type="number" step="0.01" name="cocktail_price" id="cocktail_price" value="<?php echo $row['price']; ?>"><br>

    <label for="cocktail_profit">Cocktail Profit (in RM):</label>
    <input type="number" step="0.01" name="cocktail_profit" id="cocktail_profit" value="<?php echo $row['profit']; ?>" readonly>
    <!-- Ingredients Section -->
<h2>Ingredients:</h2>
<div id="ingredients">
    <!-- Initial ingredient row -->
    <div class="ingredient-row">
        <label for="ingredient_name[]">Ingredient Name:</label>
        <select name="ingredient_name[]">
            <?php
            // Populate dropdown with alcohol names from alcohol_inventory
            $query = "SELECT DISTINCT alcohol_name FROM alcohol_inventory";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($rowOption = $result->fetch_assoc()) {
                    $optionName = $rowOption['alcohol_name'];
                    echo "<option value='" . $optionName . "'>" . $optionName . "</option>";
                }
            }
            ?>
        </select>

        <br>
        <label for="amount_ml[]">Amount (in ml):</label>
        <input type="number" step="0.01" name="amount_ml[]" id="amount_ml" >

        <button type="button" class="add-ingredient">+</button>
        <button type="button" class="remove-ingredient">-</button>
    </div>
</div>


    <input type="submit" name="submit" value="Submit" class="button">
</form>

    </div>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const addIngredientButtons = document.querySelectorAll('.add-ingredient');
            const ingredientsContainer = document.getElementById('ingredients');

            function createIngredientRow() {
                const newRow = document.createElement('div');
                newRow.className = 'ingredient-row';

                const existingRow = ingredientsContainer.querySelector('.ingredient-row');
                newRow.innerHTML = existingRow.innerHTML.replace(/ingredient_name\[\]/g, 'ingredient_name[]').replace(/ingredient_amount\[\]/g, 'ingredient_amount[]');


                ingredientsContainer.appendChild(newRow);
            }

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

            function calculateProfit() {
                const costInput = document.getElementById('cocktail_cost');
                const priceInput = document.getElementById('cocktail_price');
                const profitInput = document.getElementById('cocktail_profit');

                const cost = parseFloat(costInput.value);
                const price = parseFloat(priceInput.value);

                if (!isNaN(cost) && !isNaN(price)) {
                    const profit = price - cost;
                    profitInput.value = profit.toFixed(2);
                } else {
                    profitInput.value = '';
                }
            }

            const costInput = document.getElementById('cocktail_cost');
            const priceInput = document.getElementById('cocktail_price');
            costInput.addEventListener('change', calculateProfit);
            priceInput.addEventListener('change', calculateProfit);
        });
    </script>
</div>

</body>
</html>
