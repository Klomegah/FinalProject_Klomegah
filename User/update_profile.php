<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

$userId = $_SESSION['user_id'];
$updates = [];
$params = [];
$types = "";

// Build update query dynamically based on what's provided
if (isset($data['firstname'])) {
    $updates[] = "firstname = ?";
    $params[] = trim($data['firstname']);
    $types .= "s";
}

if (isset($data['lastname'])) {
    $updates[] = "lastname = ?";
    $params[] = trim($data['lastname']);
    $types .= "s";
}

if (isset($data['email'])) {
    // Validate email format
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'error' => 'Invalid email format']);
        exit;
    }
    
    // Check if email is already taken by another user
    $checkStmt = $con->prepare("SELECT user_id FROM LockIn_users WHERE email = ? AND user_id != ?");
    $checkStmt->bind_param("si", $data['email'], $userId);
    $checkStmt->execute();
    if ($checkStmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Email already taken']);
        exit;
    }
    $checkStmt->close();
    
    $updates[] = "email = ?";
    $params[] = trim($data['email']);
    $types .= "s";
}

if (isset($data['password'])) {
    $updates[] = "password = ?";
    $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
    $types .= "s";
}

if (empty($updates)) {
    echo json_encode(['success' => false, 'error' => 'Nothing to update']);
    exit;
}

// Add user_id to params for WHERE clause
$params[] = $userId;
$types .= "i";

// Build and execute query
$sql = "UPDATE LockIn_users SET " . implode(", ", $updates) . " WHERE user_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    // Update session if email or name changed
    if (isset($data['email'])) {
        $_SESSION['email'] = trim($data['email']);
    }
    if (isset($data['firstname'])) {
        $_SESSION['firstname'] = trim($data['firstname']);
    }
    if (isset($data['lastname'])) {
        $_SESSION['lastname'] = trim($data['lastname']);
    }
    
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update profile']);
}

$stmt->close();
?>



