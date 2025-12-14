<?php
/**
 * Account Activation Handler
 * Handles activation via popup (AJAX) or direct URL access
 */

require_once __DIR__ . '/../Connection/connection.php';

// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Get token from URL or POST
$token = isset($_GET['token']) ? trim($_GET['token']) : (isset($_POST['token']) ? trim($_POST['token']) : '');

if (empty($token)) {
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid activation link. No token provided.']);
        exit;
    }
    die('Invalid activation link. No token provided.');
}

// Find user by token and check if not expired
$stmt = $con->prepare("SELECT user_id, firstname, email, is_activated, token_expires_at FROM LockIn_users WHERE activation_token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // Token not found or expired
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Invalid or expired activation link. Please register again.']);
        exit;
    }
    die('Invalid or expired activation link. Please register again.');
}

$user = $result->fetch_assoc();

// Check if token is expired
$expiresAt = new DateTime($user['token_expires_at']);
$now = new DateTime();
if ($expiresAt < $now) {
    $stmt->close();
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Activation link has expired. Please register again.']);
        exit;
    }
    die('Activation link has expired. Please register again.');
}

// Check if already activated
if ($user['is_activated']) {
    $stmt->close();
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Account is already activated.']);
        exit;
    }
    // Already activated - redirect to login
    header("Location: ../LoginAndSignUpPages/login-html.php?message=already_activated");
    exit;
}

// Activate the account
$stmt = $con->prepare("UPDATE LockIn_users SET is_activated = TRUE, activation_token = NULL, token_expires_at = NULL WHERE activation_token = ?");
$stmt->bind_param("s", $token);

if ($stmt->execute()) {
    $stmt->close();
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Account activated successfully!']);
        exit;
    }
    // Success - redirect to login with success message
    header("Location: ../LoginAndSignUpPages/login-html.php?message=activated");
    exit;
} else {
    $stmt->close();
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Activation failed. Please try again.']);
        exit;
    }
    die('Activation failed. Please try again.');
}
?>


