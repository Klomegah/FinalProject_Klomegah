<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$userId = $_SESSION['user_id'];

// Delete user account (CASCADE will delete all related data: tasks, sessions, notes, drafts)
$stmt = $con->prepare("DELETE FROM LockIn_users WHERE user_id = ?");
$stmt->bind_param("i", $userId);

if ($stmt->execute()) {
    // Destroy session
    session_unset();
    session_destroy();
    
    echo json_encode(['success' => true, 'message' => 'Account deleted successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete account']);
}

$stmt->close();
?>

