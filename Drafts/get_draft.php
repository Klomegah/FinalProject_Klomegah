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

// Get draft for this session
$stmt = $con->prepare("SELECT draft_data FROM drafts WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => true, 'draft' => null]);
    exit;
}

$draft = $result->fetch_assoc();
echo json_encode([
    'success' => true,
    'draft' => json_decode($draft['draft_data'], true)
]);

$stmt->close();
?>

require_once '../check_auth.php';
require_once '../connection.php';
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

// Get draft for this session
$stmt = $con->prepare("SELECT draft_data FROM drafts WHERE session_id = ? AND user_id = ?");
$stmt->bind_param("ii", $sessionId, $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => true, 'draft' => null]);
    exit;
}

$draft = $result->fetch_assoc();
echo json_encode([
    'success' => true,
    'draft' => json_decode($draft['draft_data'], true)
]);

$stmt->close();
?>

