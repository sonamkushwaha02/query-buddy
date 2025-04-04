<?php
require 'config/db.php';
require 'functions/send-forgot-password-email.php'; //sending email method

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "Email is required."]);
        exit();
    }

    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "No account found with this email."]);
        exit();
    }

    // Generate reset token
    $token = bin2hex(random_bytes(32));
    $token_expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

    // Store token in the database
    $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
    $stmt->execute([$token, $token_expiry, $email]);

    // Send reset link via email
    $reset_link = "http://localhost/query-buddy/reset-password.php?token=" . $token;
    sendPasswordResetEmail($email, $reset_link);

    echo json_encode(["status" => "success", "message" => "Password reset link has been sent to your email."]);
}
?>
