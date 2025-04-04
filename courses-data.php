<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Adjust this for production security

include_once('session.php'); // Assuming this includes your DB connection
include_once('config/db.php'); 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Database connection (adjust according to your setup)
try {

    $stmt = $pdo->prepare("SELECT * FROM courses");
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'courses' => $courses]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>