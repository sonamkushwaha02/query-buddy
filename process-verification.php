<?php
session_start();
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['pending_verification_email'])) {
        echo json_encode(["status" => "error", "message" => "Session expired. Please register again."]);
        exit();
    }

    $email = $_SESSION['pending_verification_email'];
    $verification_code = trim($_POST['verification_code']);

    if (!preg_match("/^\d{6}$/", $verification_code)) {
        echo json_encode(["status" => "error", "message" => "Invalid verification code format."]);
        exit();
    }

    // Check if the code matches and fetch user info
    $stmt = $pdo->prepare("SELECT id, first_name, email FROM users WHERE email = ? AND verification_code = ? AND is_email_verified = 0");
    $stmt->execute([$email, $verification_code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Update user as verified
        $stmt = $pdo->prepare("UPDATE users SET is_email_verified = 1 WHERE email = ?");
        $stmt->execute([$email]);

        // Store user info in session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['email'] = $user['email'];

        unset($_SESSION['pending_verification_email']); // Remove session after verification

        echo json_encode([
            "status" => "success",
            "message" => "Email verification successful!",
            "redirect" => "account.php"
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid or expired verification code."]);
    }
}
?>
