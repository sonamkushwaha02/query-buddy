<?php
session_start();
require 'config/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit();
    }

    // Check if user exists
    $stmt = $pdo->prepare("SELECT id, first_name, email, password, is_email_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if email is verified
        if ($user['is_email_verified'] == 0) {
            echo json_encode(["status" => "error", "message" => "Please verify your email before logging in."]);
            exit();
        }

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['email'] = $user['email'];

            echo json_encode(["status" => "success", "message" => "Login successful!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Incorrect password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No account found with this email."]);
    }
}
?>
