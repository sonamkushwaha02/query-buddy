<?php
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = trim($_POST['token']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($token) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit();
    }

    if (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || !preg_match('/[0-9]/', $new_password) || !preg_match('/[@$!%*?&]/', $new_password)) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 8 characters, include an uppercase letter, a number, and a special character."]);
        exit();
    }

    // Check if token exists and is not expired
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "Invalid or expired reset link."]);
        exit();
    }

    // Hash new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Update password & clear reset token
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE id = ?");
    $stmt->execute([$hashed_password, $user['id']]);

    echo json_encode(["status" => "success", "message" => "Password reset successful!"]);
}
?>
