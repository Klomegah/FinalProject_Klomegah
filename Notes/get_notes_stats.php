<?php

require_once '../Authentication/check_auth.php';
require_once '../Connection/connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    // Total notes
    $notesStmt = $con->prepare("SELECT COUNT(*) as total_notes FROM feynman_notes WHERE user_id = ?");
    $notesStmt->bind_param("i", $userId);
    $notesStmt->execute();
    $totalNotes = $notesStmt->get_result()->fetch_assoc()['total_notes'];
    
    // Total sessions
    $sessionsStmt = $con->prepare("SELECT COUNT(*) as total_sessions FROM pomodoro_sessions WHERE user_id = ?");
    $sessionsStmt->bind_param("i", $userId);
    $sessionsStmt->execute();
    $totalSessions = $sessionsStmt->get_result()->fetch_assoc()['total_sessions'];
    
    // Sessions with notes
    $withNotesStmt = $con->prepare("SELECT COUNT(DISTINCT session_id) as sessions_with_notes FROM feynman_notes WHERE user_id = ?");
    $withNotesStmt->bind_param("i", $userId);
    $withNotesStmt->execute();
    $sessionsWithNotes = $withNotesStmt->get_result()->fetch_assoc()['sessions_with_notes'];
    
    $sessionsWithoutNotes = $totalSessions - $sessionsWithNotes;
    $completionRate = $totalSessions > 0 ? round(($sessionsWithNotes / $totalSessions) * 100) : 0;
    
    // Last note date
    $recentStmt = $con->prepare("SELECT MAX(created_at) as last_note_date FROM feynman_notes WHERE user_id = ?");
    $recentStmt->bind_param("i", $userId);
    $recentStmt->execute();
    $lastNoteDate = $recentStmt->get_result()->fetch_assoc()['last_note_date'];
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'total_notes' => (int)$totalNotes,
            'total_sessions' => (int)$totalSessions,
            'sessions_with_notes' => (int)$sessionsWithNotes,
            'sessions_without_notes' => (int)$sessionsWithoutNotes,
            'completion_rate' => (int)$completionRate,
            'last_note_date' => $lastNoteDate
        ]
    ]);
    
    $notesStmt->close();
    $sessionsStmt->close();
    $withNotesStmt->close();
    $recentStmt->close();
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>