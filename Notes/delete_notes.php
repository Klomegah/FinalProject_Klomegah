<?php
require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

// Only allow DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get note_id from URL or request body
$noteId = isset($_GET['note_id']) ? $_GET['note_id'] : null;

// If not in URL, try to get from request body

if (!$noteId) {
    $data = json_decode(file_get_contents('php://input'), true);
    $noteId = isset($data['note_id']) ? $data['note_id'] : null;
}

if (!$noteId) {
    echo json_encode(['success' => false, 'error' => 'Note ID is required']);
    exit;
}

$userId = $_SESSION['user_id'];

// Delete notes (only if it belongs to the user)
$stmt = $con->prepare("DELETE FROM feynman_notes WHERE note_id = ? AND user_id = ?");
$stmt->bind_param("ii", $noteId, $userId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'message' => 'Notes deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Notes not found or unauthorized']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to delete notes']);
}

$stmt->close();
?>

