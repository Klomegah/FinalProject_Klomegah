<?php
session_start();

// Destroy session
session_unset();
session_destroy();

// Check if this is an AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    // AJAX request - return JSON and let JavaScript handle redirect
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => 'Logged out successfully', 'redirect' => '../LoginAndSignUpPages/login-html.php']);
} else {
    // Regular request - redirect directly
    header('Location: ../LoginAndSignUpPages/login-html.php');
}
exit;
?>
