<?php
session_start();
include 'database.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['id'];
$username = htmlspecialchars($_SESSION['username']);

// Fetch user's profile picture from the database
$stmt = $conn->prepare("SELECT profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Set default profile picture if none is uploaded
$profileImgPath = $profile_picture ? "uploads/$profile_picture" : "default-profile.jpg";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
        }
        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
        }
        .btn-rounded {
            border-radius: 30px;
            padding: 10px 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4 text-center">
                    <img src="<?php echo $profileImgPath; ?>" alt="Profile Picture" class="profile-img">
                    <h3 class="mt-3">Welcome, <?php echo $username; ?>! 🎉</h3>
                    <p class="text-muted">Manage your profile and settings below.</p>

                    <div class="d-grid gap-3">
                        <a href="editprofile.php" class="btn btn-primary btn-rounded">✏️ Edit Profile</a>
                        <a href="logout.php" class="btn btn-danger btn-rounded">🚪 Logout</a>
                        <!-- Delete Account Button -->
                        <button class="btn btn-danger btn-rounded" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">❌ Delete Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Account Confirmation Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h4>Are you sure you want to delete your account?</h4>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="deleteaccount.php" class="btn btn-danger">Delete Account</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
