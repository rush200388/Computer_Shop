<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // if installed with composer
// OR require '../src/PHPMailer.php'; etc. if downloaded manually

function sendResetEmail($toEmail, $reset_link){
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'yourgmail@gmail.com'; // Your Gmail
        $mail->Password   = 'your_app_password';   // ⚠️ Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('yourgmail@gmail.com', 'Computer Shop');
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = "Click the link below to reset your password:<br><br>
                          <a href='$reset_link'>$reset_link</a><br><br>
                          This link will expire in 30 minutes.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
