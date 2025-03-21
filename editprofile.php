
<?php
session_start();
include 'database.php';

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginform.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch user details
$stmt = $conn->prepare("SELECT username, email, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $profile_picture);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-gray-800">Edit Profile</h2>

        <form action="updateprofile.php" method="POST" enctype="multipart/form-data">
            <div class="mt-4">
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="w-full p-2 border rounded">
            </div>

            <div class="mt-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="w-full p-2 border rounded">
            </div>

            <div class="mt-4">
                <label class="block text-gray-700">Profile Picture</label>
                <input type="file" name="profile_picture" class="w-full p-2 border rounded">
                <?php if ($profile_picture): ?>
                    <img src="<?php echo $profile_picture; ?>" class="mt-2 w-20 h-20 rounded">
                <?php endif; ?>
            </div>

            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Save Changes</button>
        </form>

        <a href="dashboard.php" class="mt-4 block text-blue-500">Back to Dashboard</a>
    </div>
</body>
</html>
