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
$sessionId = isset($_GET['session_id']) ? $_GET['session_id'] : null;

if (!$sessionId) {
    echo json_encode(['success' => false, 'error' => 'Session ID is required']);
    exit;
}

// Get single session for this user
$stmt = $con->prepare("SELECT session_id, session_date, duration, mode, tasks, completed_tasks FROM pomodoro_sessions WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Session not found']);
    exit;
}

$row = $result->fetch_assoc();
echo json_encode([
    'success' => true,
    'session' => [
        'session_id' => $row['session_id'],
        'session_date' => $row['session_date'],
        'duration' => $row['duration'],
        'mode' => $row['mode'],
        'tasks' => json_decode($row['tasks'], true) ?: [],
        'completed_tasks' => json_decode($row['completed_tasks'], true) ?: []
    ]
]);

$stmt->close();
?>



