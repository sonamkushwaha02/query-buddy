<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';

function sendVerificationEmail($email, $verification_code) {
    try {
        $mail = new PHPMailer(true);   
        
        // Server settings
        $mail->SMTPDebug = 0;                                 
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
        $mail->Subject = 'Query Buddy | Email Verification Code';

        // HTML Email Body
        $mail->Body = "
        <html>
        <head>
            <title>Email Verification</title>
            <style>
                body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
                .container { max-width: 500px; background: #fff; padding: 20px; border-radius: 5px; text-align: center; }
                .code { font-size: 24px; font-weight: bold; color: #333; margin: 20px 0; }
                .footer { font-size: 12px; color: #666; margin-top: 20px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Welcome to Query Buddy</h2>
                <p>Thank you for registering. Use the verification code below to verify your email:</p>
                <div class='code'>$verification_code</div>
                <p>If you did not request this, please ignore this email.</p>
                <div class='footer'>© " . date("Y") . " Query Buddy. All rights reserved.</div>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        return true;
        
    } catch (Exception $e) {
        return false; // Return false if email sending fails
    }
}
?>
