<?php
session_start();
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: loginform.php");
    exit();
}

$id = $_SESSION['id'];

// Fetch the user's profile picture from the database
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Delete the user's profile picture from the server if it exists
if ($profile_picture && file_exists("uploads/$profile_picture")) {
    unlink("uploads/$profile_picture"); // Delete the file from the server
}

// Delete user data from the database
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Destroy the session to log the user out
session_destroy();

// Redirect to the login page
header("Location: loginform.php");
exit();
?>
