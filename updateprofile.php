<?php
session_start();
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['id'];
$username = trim($_POST['username']);
$email = trim($_POST['email']);

// File upload handling
$profile_picture = "";
if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["profile_picture"]["name"]);
    $target_file = $target_dir . uniqid() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png'];

    if (!in_array($imageFileType, $allowed_types)) {
        die("Only JPG, JPEG, and PNG files are allowed.");
    }

    if ($_FILES["profile_picture"]["size"] > 5 * 1024 * 1024) {
        die("File size must not exceed 5MB.");
    }

    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        $profile_picture = $target_file;
    } else {
        die("Error uploading file.");
    }
}

// Update query
if ($profile_picture) {
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, profile_picture = ? WHERE id = ?");
    $stmt->bind_param("sssi", $username, $email, $profile_picture, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $email, $user_id);
}

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    header("Location: dashboard.php");
} else {
    echo "Error updating profile.";
}

$stmt->close();
$conn->close();
?>
