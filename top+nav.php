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
        <li class="dropdown">
            <a href="" class="dropbtn"><i class="fa-solid fa-window-maximize"></i> Report</a>
            <div class="dropdown-content">
                <a href="report_entry.php">Sales Data Entry</a>
                <a href="report_graph.php">Revenue Graph</a>
                <a href="piechart.php">Inventory Chart</a>

            </div>
        </li> 

        <li class="dropdown">
            <a href="menu.php" class="dropbtn"><i class="fas fa-book"></i> Menu</a>
            <div class="dropdown-content">
                <a href="add_menu.php">Add Menu Items</a>
                <a href="delete_menu.php">Delete Menu Items</a>
            </div>
        </li> 

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
</body>
</html>