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

// Get notes for this session
$stmt = $con->prepare("SELECT note_id, initial_explanation, simplified_explanation, key_concepts FROM feynman_notes WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => true, 'notes' => null]);
    exit;
}

$notes = $result->fetch_assoc();
echo json_encode([
    'success' => true,
    'notes' => [
        'note_id' => $notes['note_id'],
        'initial_explanation' => $notes['initial_explanation'],
        'simplified_explanation' => $notes['simplified_explanation'],
        'key_concepts' => $notes['key_concepts']
    ]
]);

$stmt->close();
?>

