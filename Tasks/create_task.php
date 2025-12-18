<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['task_text']) || empty(trim($data['task_text']))) {
    echo json_encode(['success' => false, 'error' => 'Task text is required']);
    exit;
}

$userId = $_SESSION['user_id'];
$taskText = trim($data['task_text']);

// Insert task
$stmt = $con->prepare("INSERT INTO tasks (user_id, task_text, completed) VALUES (?, ?, FALSE)");
$stmt->bind_param("is", $userId, $taskText);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'task_id' => $con->insert_id,
        'message' => 'Task created successfully'
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to create task']);
}

$stmt->close();
?>



