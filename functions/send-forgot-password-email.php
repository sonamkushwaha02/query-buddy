<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function sendPasswordResetEmail($email, $reset_link) {
    try {
        $mail = new PHPMailer(true);
        
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'developer.tech49@gmail.com';
        $mail->Password = 'ttwi fxts apyi aqor';
        $mail->SMTPSecure = 'TLS';
        $mail->Port = 587;

        // Sender and recipient
        $mail->setFrom('developer.tech49@gmail.com', 'Query Buddy');
        $mail->addAddress($email);

        // Email Content
        $mail->isHTML(true);
        $mail->Subject = 'Reset Your Password';

        // HTML Body
        $mail->Body = "
        <html>
        <head>
            <title>Reset Password</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
                .container { max-width: 500px; background: #fff; padding: 20px; border-radius: 5px; text-align: center; }
                .btn { display: inline-block; background: #007bff; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Password Reset Request</h2>
                <p>Click the button below to reset your password:</p>
                <a href='$reset_link' class='btn'>Reset Password</a>
                <p>If you did not request this, please ignore this email.</p>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
?>
