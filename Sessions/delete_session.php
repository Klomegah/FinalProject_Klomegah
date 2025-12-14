<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get session_id from URL or request body
$sessionId = isset($_GET['session_id']) ? $_GET['session_id'] : null;

// If not in URL, try to get from request body
if (!$sessionId) {
    $data = json_decode(file_get_contents('php://input'), true);
    $sessionId = isset($data['session_id']) ? $data['session_id'] : null;
}

if (!$sessionId) {
    echo json_encode(['success' => false, 'error' => 'Session ID is required']);
    exit;
}

$userId = $_SESSION['user_id'];

// Delete session (only if it belongs to the user - CASCADE will delete related notes and drafts)
$stmt = $con->prepare("DELETE FROM pomodoro_sessions WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Session deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Session not found or unauthorized']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete session']);
}

$stmt->close();
?>

