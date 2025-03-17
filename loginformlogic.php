<?php
session_start();
include 'database.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emailaddress']);
    $password = trim($_POST['pwd']);
    $remember = isset($_POST['rememberme']);

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

            // Set cookies if 'Remember Me' is checked
            if ($remember) {
                setcookie('user_email', $email, time() + (30 * 24 * 60 * 60), "/", "", false, true);
                setcookie('user_token', password_hash($password, PASSWORD_DEFAULT), time() + (30 * 24 * 60 * 60), "/", "", false, true);
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
