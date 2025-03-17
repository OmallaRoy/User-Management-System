
<?php
session_start();
include 'database.php'; // Ensure this file contains your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $username = trim($_POST['username']);
    $email = trim($_POST['emailaddress']);
    $password = password_hash($_POST['pwd'], PASSWORD_BCRYPT);

    // File upload handling
    $target_dir = "uploads/";
    
    // Ensure the upload directory exists, if not create it
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create directory if it doesn't exist
    }

    // Check if file is uploaded and no error occurred
    if (isset($_FILES["profile_picture"]) && $_FILES["profile_picture"]["error"] == 0) {
        $file_name = basename($_FILES["profile_picture"]["name"]);
        $target_file = $target_dir . uniqid() . "_" . $file_name;
        $imageFileType = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_size = $_FILES["profile_picture"]["size"];
        $allowed_types = ['jpg', 'jpeg', 'png'];

        // Validate file type
        if (!in_array($imageFileType, $allowed_types)) {
            die("Only JPG, JPEG, and PNG files are allowed.");
        }

        // Validate file size
        if ($file_size > 5 * 1024 * 1024) { // 5MB max
            die("File size must not exceed 5MB.");
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Insert user data into the database
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_picture) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $email, $password, $target_file);

            if ($stmt->execute()) {
                $_SESSION['id'] = $conn->insert_id; // Store user ID in session
                $_SESSION['username'] = $username;

                header("Location: dashboard.php"); // **Redirect to dashboard**
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Error uploading file. Please try again.";
        }
    } else {
        // Handle upload errors
        if (isset($_FILES["profile_picture"])) {
            $error_code = $_FILES["profile_picture"]["error"];
            echo "Error during file upload. Error code: $error_code";
        } else {
            echo "No file uploaded or there was an error with the file upload.";
        }
    }

    $conn->close();
}
?>



