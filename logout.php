<?php
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Expire the "Remember Me" cookies
setcookie("emailaddress", "", time() - 3600, "/");
setcookie("pwd", "", time() - 3600, "/");

header("Location: loginform.php"); // Redirect to login page
exit();
?>
