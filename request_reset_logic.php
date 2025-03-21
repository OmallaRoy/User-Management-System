<?php

include('database.php');
use PHPMailer\PHPMailer\PHPMailer;
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $code = rand(100000, 999999);

    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires) VALUES (?, ?, ?)");
    $expires = date("U") + 600;
    $stmt->bind_param("sss", $email, $code, $expires);
    $stmt->execute();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'omallaroy02@gmail.com';
        $mail->Password = 'mrll ozkl snwv irly';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('omallaroy02@gmail.com', 'Password Reset');
        $mail->addAddress($email);
        $mail->Subject = 'Your verification code';
        $mail->Body = "Your verification code is: $code";
        $mail->send();

        header("Location: verify_code.php?email=$email");
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}


?>