<?php
/**
 * User Profile API
 * Handles getting user profile information
 */

session_start();
require 'db.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Please log in to access profile."
    ]);
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $con->prepare("SELECT user_id, first_name, last_name, email, theme_preference FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['user_id'],
            "first_name" => $user['first_name'],
            "last_name" => $user['last_name'],
            "email" => $user['email'],
            "theme" => $user['theme_preference'] ?? 'light'
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "User not found."
    ]);
}

$stmt->close();
$con->close();
?>

