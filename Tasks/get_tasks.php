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

// Get all tasks for this user
$stmt = $con->prepare("SELECT task_id, task_text, completed FROM tasks WHERE user_id = ? ORDER BY task_id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$tasks = [];
while ($row = $result->fetch_assoc()) {
    $tasks[] = [
        'id' => $row['task_id'],
        'text' => $row['task_text'],
        'completed' => (bool)$row['completed']
    ];
}

echo json_encode([
    'success' => true,
    'tasks' => $tasks
]);

$stmt->close();
?>



