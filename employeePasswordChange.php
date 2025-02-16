<?php
session_start();

var_dump($_SESSION);

// Check if the user is logged in and if the password is still the default (lastname)
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: employeeLogin.php');
    exit();
}

// Fetch user details from the session
$user_id = $_SESSION['user_id'];
$current_password = $_SESSION['lastname']; // Default password (lastname)

// Connect to the database
include 'admin/includes/conn.php';

// Handle form submission to change password
if (isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the new password and confirm password match
    if ($new_password === $confirm_password) {
        // Update the password in the database if it's not the same as the current password
        if ($new_password !== $current_password) {
            // Update the password and set the 'password_changed' field to 1 (true)
            $sql = "UPDATE employees SET password = ?, password_changed = 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $new_password, $user_id);
            
            if ($stmt->execute()) {
                // Successfully updated the password
                $_SESSION['success'] = 'Password updated successfully!';
                // $_SESSION['lastname'] = $new_password; // Update session with new password
                header('Location: employeeHome.php');
                exit();
            } else {
                $_SESSION['error'] = 'Failed to update password. Please try again.';
            }
        } else {
            $_SESSION['error'] = 'New password cannot be the same as the default password (lastname).';
        }
    } else {
        $_SESSION['error'] = 'Passwords do not match. Please try again.';
    }
}

// Redirect if password is already changed (not the default lastname)
if ($_SESSION['lastname'] !== $current_password) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="employeeCSS.css"> <!-- Include your custom styles here -->
</head>
<body>
    <div class="change-password-container">
        <h2>Change Password</h2>
        
        <!-- Display error or success message -->
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

        <form action="employeePasswordChange.php" method="post">
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" placeholder="Enter new password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm new password" required>
            </div>
            <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
        </form>
    </div>
</body>
</html>
