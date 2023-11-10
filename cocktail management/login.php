<?php
session_start();
require 'config.php'; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // You should sanitize and validate user input here
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $password = $_POST['password'];
    
        // Sanitize user input
        $name = trim(mysqli_real_escape_string($conn, $name));
        $password = trim(mysqli_real_escape_string($conn, $password));
    
        // Validate user input
        if (empty($name) || empty($password)) {
            $login_error = "Please enter both name and password.";
        } else {
            // Continue with the database query
            // ...
        }
    }
    // Query the database to check if the name and password match
    $query = "SELECT * FROM users WHERE name = '$name' AND password = '$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // User exists, redirect to home.php
        $_SESSION['name'] = $name;
        header("Location: home.php");
    } else {
        // User does not exist or incorrect credentials, display an error message
        $login_error = "User credentials are incorrect.";
    }
    // Store the user's name in a session variable
    $_SESSION['name'] = $name;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Cocktail Inventory Management</title>
    <link rel="stylesheet" href="style.css"> <!-- Link your CSS file here -->
    <style>
        /* Add your custom styles here */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('images/bar2.jpg'); /* Replace 'your-background-image.jpg' with your image file path */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .login-container {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-box h2 {
            margin: 0;
            color: #333;
        }

        .login-form {
            margin-top: 20px;
        }

        .login-form input[type="text"], .login-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .login-form input[type="submit"] {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .login-form input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }

        .register-link {
            text-align: center;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>Login to Cocktail Inventory</h2>
            <form class="login-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="text" name="name" placeholder="Name" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="submit" value="Login">
            </form>
            <!-- Error message container -->
            <?php if (isset($login_error)) { ?>
                <p class="error-message"><?php echo $login_error; ?></p>
            <?php } ?>
            <!-- Registration link -->
            <p class="register-link">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
