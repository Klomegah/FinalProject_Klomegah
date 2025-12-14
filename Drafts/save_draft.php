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
if (!isset($data['session_id']) || !isset($data['draft_data'])) {
    echo json_encode(['success' => false, 'error' => 'Session ID and draft data are required']);
    exit;
}

$userId = $_SESSION['user_id'];
$sessionId = $data['session_id'];
$draftData = json_encode($data['draft_data']);

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

// Check if draft already exists
$stmt = $con->prepare("SELECT draft_id FROM drafts WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing draft
    $stmt = $con->prepare("UPDATE drafts SET draft_data = ? WHERE session_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $draftData, $sessionId, $userId);
} else {
    // Insert new draft
    $stmt = $con->prepare("INSERT INTO drafts (session_id, user_id, draft_data) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sessionId, $userId, $draftData);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Draft saved successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save draft']);
}

$stmt->close();
?>

require_once '../check_auth.php';
require_once '../connection.php';
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['session_id']) || !isset($data['draft_data'])) {
    echo json_encode(['success' => false, 'error' => 'Session ID and draft data are required']);
    exit;
}

$userId = $_SESSION['user_id'];
$sessionId = $data['session_id'];
$draftData = json_encode($data['draft_data']);

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

// Check if draft already exists
$stmt = $con->prepare("SELECT draft_id FROM drafts WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing draft
    $stmt = $con->prepare("UPDATE drafts SET draft_data = ? WHERE session_id = ? AND user_id = ?");
    $stmt->bind_param("sii", $draftData, $sessionId, $userId);
} else {
    // Insert new draft
    $stmt = $con->prepare("INSERT INTO drafts (session_id, user_id, draft_data) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sessionId, $userId, $draftData);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Draft saved successfully']);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to save draft']);
}

$stmt->close();
?>

