<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
try {
    //Server settings
    $mail->SMTPDebug = 3;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'vsodeen@gmail.com';                 // SMTP username
    $mail->Password = 'Charlie@2590!';                           // SMTP password
    $mail->SMTPSecure = 'TLS';                           
    $mail->Port = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('vsodeen@gmail.com');
    $mail->addAddress('mithilesh849@gmail.com');     // Add a recipient

    //Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Test subject';
    $mail->Body    = 'Hello mail testing';

    $mail->send();
    echo "Sent!";
    exit();
} catch (Exception $e) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}
?>