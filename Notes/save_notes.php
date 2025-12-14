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
if (!isset($data['session_id'])) {
    echo json_encode(['success' => false, 'error' => 'Session ID is required']);
    exit;
}

$userId = $_SESSION['user_id'];
$sessionId = $data['session_id'];
$initialExplanation = isset($data['initial_explanation']) ? trim($data['initial_explanation']) : '';
$simplifiedExplanation = isset($data['simplified_explanation']) ? trim($data['simplified_explanation']) : '';
$keyConcepts = isset($data['key_concepts']) ? trim($data['key_concepts']) : '';

// Check if session belongs to user
$stmt = $con->prepare("SELECT session_id FROM pomodoro_sessions WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Session not found']);
    exit;
}
$stmt->close();

// Check if notes already exist for this session
$stmt = $con->prepare("SELECT note_id FROM feynman_notes WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing notes
    $stmt = $con->prepare("UPDATE feynman_notes SET initial_explanation = ?, simplified_explanation = ?, key_concepts = ? WHERE session_id = ? AND user_id = ?");
    $stmt->bind_param("sssii", $initialExplanation, $simplifiedExplanation, $keyConcepts, $sessionId, $userId);
} else {
    // Insert new notes
    $stmt = $con->prepare("INSERT INTO feynman_notes (session_id, user_id, initial_explanation, simplified_explanation, key_concepts) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $sessionId, $userId, $initialExplanation, $simplifiedExplanation, $keyConcepts);
}

if ($stmt->execute()) {
    // Delete draft after saving notes
    $deleteDraft = $con->prepare("DELETE FROM drafts WHERE session_id = ? AND user_id = ?");
    $deleteDraft->bind_param("ii", $sessionId, $userId);
    $deleteDraft->execute();
    $deleteDraft->close();
    
    echo json_encode(['success' => true, 'message' => 'Notes saved successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save notes']);
}

$stmt->close();
?>

