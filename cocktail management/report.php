<?php
// Start the session
session_start();

// Check if the user's name is stored in the session
if (isset($_SESSION['name'])) {
    $name = $_SESSION['name'];
} else {
    // Redirect to the login page if the user is not authenticated
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cocktail Inventory Management System</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
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
    /* color: black; */
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

/* Change color on hover */
.dropdown-content a:hover {
    background-color: #333;
}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {
    display: block;
    
}

/* Additional style for the header */
header {
    background-color: #333;
    padding: 10px;
    color: white;
    text-align: center;
}

/* Additional style for the container */
#container {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Additional style for the chart container */
#chart-container {
    width: 100%;
    max-width: 800px;
    margin: 20px auto;
}
</style>
</head>
<body>
    
    <header>
    <h1><a href="home.php" style="color: white; text-decoration: none;">Cocktail Inventory Management System</a></h1>
    <div style="text-align: right;">
    <p style="color:#FFFFFF;">
        <div class="dropdown">
            Hi, <?php echo $name; ?>
            <i class="fa fa-caret-down"></i>
            <div class="dropdown-content" id="myDropdown">
              <a href="logout.php" style="color:#FFFFFF;">Log Out</a>

            </div>
        </div>
    </p>
    </div>

    </header>
    <nav>
    <ul class="nav-links">
        <li class="dropdown">
            <a href="add_cocktail.php" class="dropbtn"><i class="fas fa-plus"></i> Add Alcohol</a>
            <div class="dropdown-content">
                <a href="add_cocktail.php">Add New</a>
                <a href="add_cocktail_history.php">Add Alcohol History</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="update_cocktail.php" class="dropbtn"><i class="fas fa-edit"></i> Update Alcohol</a>
            <div class="dropdown-content">
                <a href="update_cocktail.php">Update Existing Alcohol</a>
                <a href="update_cocktail_history.php">Update Alcohol History</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="delete_cocktail.php" class="dropbtn"><i class="fas fa-trash"></i> Delete Alcohol</a>
            <div class="dropdown-content">
                <a href="delete_cocktail.php">Delete New</a>
                <a href="delete_cocktail_history.php">Delete Alcohol History</a>
            </div>
        </li>
        <li><a href="report.php"><i class="fa-solid fa-window-maximize"></i> Report</a></li> 
        <li><a href="menu.php"><i class="fas fa-book"></i> Menu</a></li> 
        <li class="dropdown">
            <a href="create_cocktail.php" class="dropbtn"><i class="fas fa-plus"></i> Create Cocktail</a>
            <div class="dropdown-content">
                <a href="create_cocktail.php">Create New</a>
                <a href="create_cocktail_history.php">Create Cocktail History</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="edit_cocktail.php" class="dropbtn"><i class="fas fa-edit"></i> Edit Cocktail</a>
            <div class="dropdown-content">
                <a href="edit_cocktail.php">Edit Existing Cocktail</a>
                <a href="edit_cocktail_history.php">Edit Cocktail History</a>
            </div>
        </li>
        <li class="dropdown">
            <a href="remove_cocktail.php" class="dropbtn"><i class="fas fa-trash"></i> Delete Cocktail</a>
            <div class="dropdown-content">
                <a href="remove_cocktail.php">Delete New</a>
                <a href="remove_cocktail_history.php">Delete Cocktail History</a>
            </div>
        </li>
    </ul>


</nav>
<?php
require('config.php');
?>

<body>
    <title>Revenue Chart</title>
    <!-- Include Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div id="chart-container">
        <h2>Revenue Chart</h2>
            <!-- User input for calendar, from, and to dates -->
            <label for="from">From:</label>
            <input type="date" id="from">
            <label for="to">To:</label>
            <input type="date" id="to">
        <label for="cocktail_name">Cocktail:</label>
        <select id="cocktail_name"></select>
        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" placeholder="Enter quantity">
        <button onclick="addData()">Add Data</button>
        <canvas id="revenueChart"></canvas>
    </div>

    <script>
        let chartData = {
            labels: [],
            datasets: [{
                label: 'Quantity',
                data: [],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        };

        let ctx = document.getElementById('revenueChart').getContext('2d');
        let myChart = new Chart(ctx, {
            type: 'bar',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Fetch cocktail list from the server
        <?php
        // Fetch cocktail list from the database
        $query = "SELECT cocktail_id, cocktail_name FROM cocktail_list";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $cocktailList = [];
            while ($row = $result->fetch_assoc()) {
                $cocktailList[] = [
                    'value' => $row['cocktail_id'],
                    'text' => $row['cocktail_name'],
                ];
            }
            echo "let cocktailDropdown = document.getElementById('cocktail_name');";
            echo "let cocktailList = " . json_encode($cocktailList) . ";";
            echo "cocktailList.forEach(cocktail => {";
            echo "    let option = document.createElement('option');";
            echo "    option.value = cocktail.value;";
            echo "    option.textContent = cocktail.text;";
            echo "    cocktailDropdown.appendChild(option);";
            echo "});";
        }

        // Close the database connection
        $conn->close();
        ?>

        // Fetch cocktail data from the server
        fetch('report_get_cocktail_data.php')
            .then(response => response.json())
            .then(data => {
                // Use data to update cocktailDropdown or cocktailPriceMap
                let cocktailDropdown = document.getElementById('cocktail_name');

                data.forEach(cocktail => {
                    let option = document.createElement('option');
                    option.value = cocktail.name;
                    option.textContent = cocktail.name;
                    cocktailDropdown.appendChild(option);

                    // You can also update cocktailPriceMap if needed
                    // cocktailPriceMap.set(cocktail.name, cocktail.price);
                });
            });
               // Keep track of total revenue for each cocktail
               let totalRevenueMap = new Map();

function addData() {
    let cocktailDropdown = document.getElementById('cocktail_name');
    let quantityInput = document.getElementById('quantity');
    let fromInput = document.getElementById('from');
    let toInput = document.getElementById('to');

    // Validate user inputs as needed
    if (quantityInput.value > 0) {
        let selectedCocktail = cocktailDropdown.options[cocktailDropdown.selectedIndex].text;
        chartData.labels.push(selectedCocktail);
        chartData.datasets[0].data.push(parseFloat(quantityInput.value));

        myChart.update();

        // Update total revenue for the selected cocktail
        updateTotalRevenue(selectedCocktail, parseFloat(quantityInput.value));

        // Clear input fields
        quantityInput.value = '';
    } else {
        alert('Please enter a valid quantity.');
    }
}

function updateTotalRevenue(cocktailName, quantity) {
    // Calculate total revenue and update the map
    let totalRevenue = totalRevenueMap.get(cocktailName) || 0;
    totalRevenue += quantity * getCocktailPrice(cocktailName); // You need to implement getCocktailPrice function

    // Update the map
    totalRevenueMap.set(cocktailName, totalRevenue);

    // Display the total revenue to the user
    document.getElementById('totalRevenue').innerHTML = generateTotalRevenueHTML();
}

function generateTotalRevenueHTML() {
    let html = '<p>Total Revenue:</p>';
    totalRevenueMap.forEach((value, key) => {
        html += `<p>${key}: ${value}</p>`;
    });
    return html;
}

function getCocktailPrice(cocktailName) {
            // You can directly use the data fetched from the server
            let cocktail = data.find(item => item.name === cocktailName);
            return cocktail ? cocktail.price : 0;
        }
    </script>
</body>
</html>
