<?php
/**
 * Delete Account Handler
 * Handles account deletion with confirmation
 */

session_start();
require 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in to delete your account."
    ]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput, true);
    
    // Verify confirmation text
    if (!isset($input['confirmation']) || strtolower(trim($input['confirmation'])) !== 'delete') {
        echo json_encode([
            "success" => false,
            "message" => "Please type 'Delete' to confirm account deletion."
        ]);
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    
    // Delete user account
    $stmt = $con->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        // Destroy session
        session_unset();
        session_destroy();
        
        echo json_encode([
            "success" => true,
            "message" => "Your account has been deleted."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to delete account. Please try again."
        ]);
    }
    
    $stmt->close();
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
}

$con->close();
?>

