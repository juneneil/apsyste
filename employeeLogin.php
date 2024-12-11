<?php
// Start the session
session_start();

session_unset();  // Clear all session variables
session_destroy();  // Destroy the session

session_start();

var_dump($_SESSION);

// Include database connection
include 'admin/includes/conn.php'; // Adjust the path if needed

// Check if the form is submitted
if (isset($_POST['login'])) {
    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];  // Default password will be handled in the server logic

    // Check if the email and password are not empty
    if (!empty($email) && !empty($password)) {
        // Prepare a SQL query to fetch the user from the 'employees' table (or another correct table)
        $sql = "SELECT * FROM employees WHERE email = ? LIMIT 1";  // Use correct table name
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Check if the entered password matches the stored password
            if ($password == $user['password']) {
                // Store session data
                $_SESSION['user_id'] = $user['id'];  // Assuming 'id' is the user's primary key
                $_SESSION['email'] = $user['email'];
                $_SESSION['firstname'] = $user['firstname'];  // Add more user data
                $_SESSION['lastname'] = $user['lastname'];  // Add more user data
                $_SESSION['position'] = $user['position'];  // Add more user data
                $_SESSION['success'] = 'Login successful!';

                // If the password was still the default (lastname), redirect to change password page
                if ($user['password'] == $user['lastname']) {
                    header('Location: employeePasswordChange.php'); 
                    exit();
                } else {
                    // Redirect to the main page if the password has been updated
                    header('Location: index.php'); 
                    exit();
                }
            } else {
                $_SESSION['error'] = 'Invalid password';
            }
        } else {
            $_SESSION['error'] = 'User not found';
        }
    } else {
        $_SESSION['error'] = 'Please fill in both fields';
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <link rel="stylesheet" href="employeeCSS.css">
</head>
<body>

    <div>
        <img src="employeeImageLogin.jpg" alt="Pateros Building">
    </div>

    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error or success message from the PHP session -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Login form -->
        <form action="employeeLogin.php" method="post">
            <label for="email"></label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>

            <label for="password">Password (Default: Lastname):</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <input type="submit" name="login" value="Login">

            <a href="#" class="forgot-password">Forgot your password?</a>
        </form>
    </div>

</body>
</html>
