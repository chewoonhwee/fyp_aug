<?php
require 'config.php'; // Include your database connection code here

// Define variables for form data and error messages
$name = $email = $password = "";
$nameErr = $emailErr = $passwordErr = "";
$message = ""; // Initialize the message variable

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = strtoupper(test_input($_POST["name"])); // Convert name to uppercase
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

 // Validate password
 if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
} elseif (strlen($_POST["password"]) < 8) {
    $passwordErr = "Password must be at least 8 characters long";
} elseif (!preg_match("/[a-zA-Z]/", $_POST["password"]) || !preg_match("/\d/", $_POST["password"])) {
    $passwordErr = "Password must contain both letters and numbers";
} else {
    $password = test_input($_POST["password"]);
}

// Validate password confirmation
if (empty($_POST["password_confirmation"])) {
    $passwordConfErr = "Password confirmation is required";
} elseif ($_POST["password_confirmation"] !== $_POST["password"]) {
    $passwordConfErr = "Password confirmation does not match the password";
} else {
    $password_confirmation = test_input($_POST["password_confirmation"]);
}

    // If there are no errors, you can save the data to the database
    if (empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
        // Check if the user with the same name or email already exists in the database
        $checkQuery = "SELECT * FROM users WHERE name='$name' OR email='$email'";
        $result = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($result) > 0) {
            // User already exists
            $message = "This user is already created. Please enter again.";
        } else {
            // User doesn't exist, proceed with registration
            // $passwordHash = password_hash($password, PASSWORD_BCRYPT); // Securely hash the password

            // Insert the new user into the 'users' table
            $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

            if (mysqli_query($conn, $sql)) {
                // Registration successful, redirect to the login page
                header("Location: login.php");
                exit();
            } else {
                $message = "Error: " . mysqli_error($conn);
            }
        }
    }
}

// Function to sanitize and validate user input
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" href="styles.css">
    <style>
    body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background-image: url('images/bar2.jpg'); /* Specify your background image path */
    background-size: cover;
    background-position: center;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 5px;
    text-align: center;
}

.registration-form {
    width: 300px;
    margin: 0 auto;
}

.form-group {
    text-align: left;
    margin: 10px 0;
}

label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

input {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 3px;
    margin: 5px 0;
}

button {
    background-color: #007BFF;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
}
</style>
</head>
<body>
    <div class="container">
    <form class="register-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="text" name="name" placeholder="Name" value="<?php echo $name; ?>">
            <span class="error-message"><?php echo $nameErr; ?></span>
            <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>">
            <span class="error-message"><?php echo $emailErr; ?></span>
            <input type="password" name="password" placeholder="Password">
            <span class="error-message"><?php echo $passwordErr; ?></span>
            <span class="error-message"><?php echo $message; ?></span>
            <input type="submit" value="Register">
            <p class="login-link">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</body>
</html>

