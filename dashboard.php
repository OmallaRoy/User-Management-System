<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: loginform.php"); // Redirect to login if not authenticated
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
        <p class="mt-4 text-gray-600">You have successfully logged in.</p>
        <a href="logout.php" class="mt-4 inline-block bg-red-500 text-white px-4 py-2 rounded">Logout</a>
    </div>
</body>
</html>
