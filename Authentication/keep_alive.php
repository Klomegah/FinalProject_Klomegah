<?php
/**
 * Session Keep-Alive Endpoint
 * 
 * This endpoint simply refreshes the PHP session to prevent expiration
 * during long-running activities like Pomodoro timers or note-taking.
 * 
 * It's a lightweight endpoint that just touches the session to reset
 * the inactivity timer.
 */

session_start();

// Just accessing the session refreshes it
// Return a simple success response
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Session refreshed'
]);
?>
