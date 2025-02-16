<?php
session_start(); // Start the session

var_dump($_SESSION);  // Output session data

// Prevent caching of the page (e.g., for login and logout)
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Destroy the session and clear session variables
session_unset();  // Clear all session variables
session_destroy();  // Destroy the session

// Remove a single session variable
unset($_SESSION['firstname']);
unset($_SESSION['lastname']);
unset($_SESSION['position']);

// Optional: Expire session cookie by setting an expiry time in the past
// if (ini_get("session.use_cookies")) {
//     $params = session_get_cookie_params();
//     setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
// }

// Regenerate session ID to prevent session fixation
// session_regenerate_id(true);

// Redirect to the login page after logging out
header('Location: employeeLogin.php');
exit();  // Stop further script execution
?>
