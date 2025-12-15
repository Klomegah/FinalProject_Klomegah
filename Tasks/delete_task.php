<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get task_id from URL parameter (RESTful standard for DELETE)
$taskId = isset($_GET['task_id']) ? intval($_GET['task_id']) : null;

if (!$taskId) {
    echo json_encode(['success' => false, 'error' => 'Task ID is required in URL parameter']);
    exit;
}

$userId = $_SESSION['user_id'];

// Delete task (only if it belongs to the user)
$stmt = $con->prepare("DELETE FROM tasks WHERE task_id = ? AND user_id = ?");
$stmt->bind_param("ii", $taskId, $userId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Task deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Task not found or unauthorized']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete task']);
}

$stmt->close();
?>


