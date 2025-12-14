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

// Get ALL sessions for this user with notes check
$stmt = $con->prepare("
    SELECT 
        ps.session_id, 
        ps.session_date, 
        ps.duration, 
        ps.mode, 
        ps.tasks, 
        ps.completed_tasks,
        CASE WHEN fn.note_id IS NOT NULL THEN 1 ELSE 0 END as has_notes
    FROM pomodoro_sessions ps
    LEFT JOIN feynman_notes fn ON ps.session_id = fn.session_id
    WHERE ps.user_id = ? 
    ORDER BY ps.session_date DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$sessions = [];
while ($row = $result->fetch_assoc()) {
    $sessions[] = [
        'session_id' => $row['session_id'],
        'session_date' => $row['session_date'],
        'duration' => (int)$row['duration'],
        'mode' => $row['mode'],
        'tasks' => json_decode($row['tasks'], true) ?: [],
        'completed_tasks' => json_decode($row['completed_tasks'], true) ?: [],
        'has_notes' => (bool)$row['has_notes']
    ];
}

echo json_encode([
    'success' => true,
    'sessions' => $sessions
]);

$stmt->close();
?> 