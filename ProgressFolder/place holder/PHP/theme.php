<?php
/**
 * Theme Management API
 * Handles getting and updating user theme preferences
 */

session_start();
require 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in to access theme settings."
    ]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'];

if ($method === 'GET') {
    // Get user's theme preference
    $stmt = $con->prepare("SELECT theme_preference FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $theme = $user['theme_preference'] ?? 'light';
        echo json_encode([
            "success" => true,
            "theme" => $theme
        ]);
    } else {
        echo json_encode([
            "success" => true,
            "theme" => "light"
        ]);
    }
    $stmt->close();
    
} elseif ($method === 'POST') {
    // Update user's theme preference
    $rawInput = file_get_contents("php://input");
    $input = json_decode($rawInput, true);
    
    if (!isset($input['theme'])) {
        echo json_encode([
            "success" => false,
            "message" => "Theme value is required."
        ]);
        exit();
    }
    
    $theme = $input['theme'];
    if (!in_array($theme, ['light', 'dark'])) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid theme value."
        ]);
        exit();
    }
    
    // Check if theme_preference column exists, if not add it
    $checkColumn = $con->query("SHOW COLUMNS FROM users LIKE 'theme_preference'");
    if ($checkColumn->num_rows == 0) {
        $con->query("ALTER TABLE users ADD COLUMN theme_preference VARCHAR(10) DEFAULT 'light'");
    }
    
    $stmt = $con->prepare("UPDATE users SET theme_preference = ? WHERE user_id = ?");
    $stmt->bind_param("si", $theme, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "Theme updated successfully.",
            "theme" => $theme
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Failed to update theme."
        ]);
    }
    $stmt->close();
}

$con->close();
?>

