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
<?php

if (count($_POST) > 0) {
    
    $alcohol_id = $_POST['alcohol_id'];

    // Retrieve the original data from alcohol_inventory
    $original_data_result = mysqli_query($conn, "SELECT * FROM alcohol_inventory WHERE alcohol_id = '" . $alcohol_id . "'");
    $original_data = mysqli_fetch_array($original_data_result);

    // Insert the original data into the update_history table
    mysqli_query($conn, "INSERT INTO update_history (alcohol_id, alcohol_category, alcohol_name, price, quantity, update_timestamp, name)
                         VALUES (
                            '" . $original_data['alcohol_id'] . "',
                            '" . $original_data['alcohol_category'] . "',
                            '" . $original_data['alcohol_name'] . "',
                            '" . $original_data['price'] . "',
                            '" . $original_data['quantity'] . "',
                            NOW(),
                            '" . $name . "')");

    // Update the record with the updated_timestamp
    mysqli_query($conn, "UPDATE alcohol_inventory SET
                        alcohol_category='" . $_POST['alcohol_category'] . "',
                        alcohol_name='" . $_POST['alcohol_name'] . "',
                        price='" . $_POST['price'] . "',
                        quantity='" . $_POST['quantity'] . "',
                        updated_timestamp=NOW()
                        WHERE alcohol_id ='" . $_POST['alcohol_id'] . "'");
    echo '<script>alert("Record Modified Successfully");</script>';
    echo '<script>window.location.href = "home.php";</script>';
}

$result = mysqli_query($conn,"SELECT * FROM alcohol_inventory WHERE alcohol_id='" . $_GET['alcohol_id'] . "'");
$row= mysqli_fetch_array($result);
?>

<html>
<head>
<title>Update Alcohol</title>
</head>
<body>
    
<div class="content">
<div class="form-container">
        <h2>Update Alcohol</h2>
        <form name="alcohol_details" method="post" action="">
            <div><?php if(isset($message)) { echo $message; } ?></div>
            <div style="padding-bottom:5px;"></div>
            
            Alcohol Category: <br>
            <input type="text" name="alcohol_category" class="txtField" value="<?php echo $row['alcohol_category']; ?>">
            <br>
            Alcohol Name: <br>
            <input type="text" name="alcohol_name" class="txtField" value="<?php echo $row['alcohol_name']; ?>">
            <br>
            Price :<br>
            <input type="text" name="price" class="txtField" value="<?php echo $row['price']; ?>">
            <br>
            Quantity :<br>
            <input type="text" name="quantity" class="txtField" value="<?php echo $row['quantity']; ?>">
            <br>
            <input type="hidden" name="alcohol_id" value="<?php echo $row['alcohol_id']; ?>">
            <input type="submit" name="submit" value="Submit" class="button">
            </div>
        </form>
    </div>
</body>
</html>