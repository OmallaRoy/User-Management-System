<?php
// 4. verify_code_logic.php
include('database.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $code = $_POST['code'];

    $stmt = $conn->prepare("SELECT expires FROM password_resets WHERE email = ? AND token = ?");
    $stmt->bind_param("ss", $email, $code);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($expires);
        $stmt->fetch();

        if ($expires >= time()) {
            header("Location: new_password.php?email=$email");
        } else {
            echo "Code expired!";
        }
    } else {
        echo "Invalid code!";
    }
}

?>