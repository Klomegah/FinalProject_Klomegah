<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get user profile
$stmt = $con->prepare("SELECT user_id, firstname, lastname, email FROM LockIn_users WHERE user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit;
}

$user = $result->fetch_assoc();
echo json_encode([
    'success' => true,
    'user' => [
        'user_id' => $user['user_id'],
        'firstname' => $user['firstname'],
        'lastname' => $user['lastname'],
        'email' => $user['email']
    ]
]);

$stmt->close();
?>

