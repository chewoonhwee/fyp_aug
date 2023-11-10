<?php
require('top+nav.php'); // Include your header/navigation file
require('config.php'); // Include your database connection code
?>
<style>
/* Style for the search bar */
form {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
    display: flex; /* Make the form a flex container */
    flex-wrap: wrap; /* Wrap items to the next line when needed */
    justify-content: space-between; /* Place items at the beginning and end */
    align-items: center; /* Vertically center items */
}

/* Style for labels */
label {
    flex: 1;
    margin-right: 10px;
    text-align: left;
}

/* Style for text inputs */
input[type="text"] {
    flex: 1; /* Adjust the width as needed */
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
}

/* Style for the search button */
input[type="submit"] {
    flex: 1;
    background-color: #007BFF;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}
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
</head>
<body>
<main>
    <div class="content">
        <!-- Search Bar -->
        <h2>Search Cocktail</h2>
            <form method="POST" action="search_edited_cocktail_results.php">
                <div class="form-group">
                    <div>
                        <label for="search_name">Cocktail Name:</label>
                        <input type="text" name="search_name" id="search_name">
                    </div>
                </div>
                <input type="submit" value="Search">
            </form>
    </div>
        <?php
        require('config.php');
        ?>
</main>
</body>
</html>