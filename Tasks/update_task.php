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

// Validate required fields
if (!isset($data['task_id'])) {
    echo json_encode(['success' => false, 'error' => 'Task ID is required']);
    exit;
}

$userId = $_SESSION['user_id'];
$taskId = $data['task_id'];

// Check if task belongs to user
$stmt = $con->prepare("SELECT task_id FROM tasks WHERE task_id = ? AND user_id = ?");
$stmt->bind_param("ii", $taskId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Task not found']);
    exit;
}

// Update task
if (isset($data['task_text'])) {
    // Update task text
    $taskText = trim($data['task_text']);
    $stmt = $con->prepare("UPDATE tasks SET task_text = ? WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $taskText, $taskId, $userId);
} elseif (isset($data['completed'])) {
    // Toggle completion status
    $completed = $data['completed'] ? 1 : 0;
    $stmt = $con->prepare("UPDATE tasks SET completed = ? WHERE task_id = ? AND user_id = ?");
    $stmt->bind_param("iii", $completed, $taskId, $userId);
} else {
    echo json_encode(['success' => false, 'error' => 'Nothing to update']);
    exit;
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Task updated successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to update task']);
}

$stmt->close();
?>


