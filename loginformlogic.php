<?php
session_start();
include 'database.php';

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailaddress']);
    $password = trim($_POST['pwd']);
    $remember = isset($_POST['rememberme']);

    // Validate input
    if (empty($email) || empty($password)) {
        die("Email and password are required.");
    }

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $username, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['just_logged_in'] = true; // Flag for welcome popup

            // Set "Remember Me" cookies securely (valid for 7 days)
            if ($remember) {
                setcookie('user_email', $email, time() + (7 * 24 * 60 * 60), "/", "", false, true);
                setcookie('user_token', hash_hmac('sha256', $id . 'secretkey', 'customsalt'), time() + (7 * 24 * 60 * 60), "/", "", false, true);
            } else {
                setcookie('user_email', '', time() - 3600, "/");
                setcookie('user_token', '', time() - 3600, "/");
            }

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            die("Invalid password.");
        }
    } else {
        die("User not found.");
    }

    $stmt->close();
    $conn->close();
}
?>
