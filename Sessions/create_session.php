<?php


require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';

// Set JSON header
if (!headers_sent()) {
    header('Content-Type: application/json');
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON data from request
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// Check if JSON decode failed
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        'success' => false, 
        'error' => 'Invalid JSON data',
        'json_error' => json_last_error_msg(),
        'raw_input' => substr($rawInput, 0, 200)
    ]);
    exit;
}

// Validate required fields
if (!isset($data['session_date']) || !isset($data['duration']) || !isset($data['mode'])) {
    echo json_encode([
        'success' => false, 
        'error' => 'Session date, duration, and mode are required',
        'received_data' => array_keys($data ?? [])
    ]);
    exit;
}

$userId = $_SESSION['user_id'];
// Use the local time string directly (already in MySQL datetime format: Y-m-d H:i:s)
// No timezone conversion needed since JavaScript sends local time
$sessionDate = $data['session_date'];
$duration = intval($data['duration']);
$mode = $data['mode'];
$tasks = isset($data['tasks']) ? json_encode($data['tasks']) : json_encode([]);
$completedTasks = isset($data['completed_tasks']) ? json_encode($data['completed_tasks']) : json_encode([]);


// Insert session
$stmt = $con->prepare("INSERT INTO pomodoro_sessions (user_id, session_date, duration, mode, tasks, completed_tasks) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("isisss", $userId, $sessionDate, $duration, $mode, $tasks, $completedTasks);

if ($stmt->execute()) {
    $sessionId = $con->insert_id;
    echo json_encode([
        'success' => true,
        'session_id' => $sessionId,
        'message' => 'Session created successfully',
        'debug' => [
            'user_id' => $userId,
            'session_date_stored' => $sessionDate,
            'duration' => $duration,
            'mode' => $mode
        ]
    ]);
} else {
    $error = $con->error;
    $stmtErr = $stmt->error;
    echo json_encode([
        'success' => false, 
        'error' => 'Failed to create session',
        'db_error' => $error,
        'stmt_error' => $stmtErr,
        'user_id' => $userId,
        'session_date' => $sessionDate,
        'duration' => $duration,
        'mode' => $mode
    ]);
}

$stmt->close();
?>


