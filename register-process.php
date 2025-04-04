<?php
session_start(); // Start session
require 'config/db.php'; // Include database connection
include_once('functions/send-verification-email.php');  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    $first_name = trim($_POST['first_name']);
    $last_name = isset($_POST['last_name']) ? trim($_POST['last_name']) : ''; // Optional field
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Generate a 6-digit numeric verification code
    $verification_code = rand(100000, 999999); 

    // Validate First Name
    if (empty($first_name)) {
        $errors['first_name'] = "First Name is required";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $first_name)) {
        $errors['first_name'] = "Only letters and spaces allowed";
    }

    // Validate Email
    if (empty($email)) {
        $errors['email'] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format";
    } else {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = "Email is already registered";
        }
    }

    // Validate Phone
    if (empty($phone)) {
        $errors['phone'] = "Phone number is required";
    } elseif (!preg_match("/^\d{10}$/", $phone)) {
        $errors['phone'] = "Enter a valid 10-digit phone number";
    }

    // Validate Password (Strong Password)
    if (empty($password)) {
        $errors['password'] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters";
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $errors['password'] = "Password must contain at least one uppercase letter";
    } elseif (!preg_match('/[a-z]/', $password)) {
        $errors['password'] = "Password must contain at least one lowercase letter";
    } elseif (!preg_match('/[0-9]/', $password)) {
        $errors['password'] = "Password must contain at least one number";
    } elseif (!preg_match('/[@$!%*?&]/', $password)) {
        $errors['password'] = "Password must contain at least one special character (@$!%*?&)";
    }

    // Validate Confirm Password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Confirm Password is required";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match";
    }

    // If errors exist, return them
    if (!empty($errors)) {
        echo json_encode(["status" => "error", "errors" => $errors]);
        exit;
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert into database with verification_code & is_email_verified
    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, email, phone, password, is_email_verified, verification_code) 
                           VALUES (?, ?, ?, ?, ?, ?, ?)");
    $result = $stmt->execute([$first_name, $last_name, $email, $phone, $hashed_password, 0, $verification_code]);

    if ($result) {

        $_SESSION['pending_verification_email'] = $email; // Store email in session
        // send verification email 
        sendVerificationEmail($email, $verification_code);

        echo json_encode([
            "status" => "success",
            "message" => "Registration successful! Please verify your email."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "errors" => ["general" => "Something went wrong. Please try again."]
        ]);
    }
}
?>
