<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Don't display errors, but log them
ini_set('log_errors', 1);

// Catch any fatal errors and return JSON response
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => 'Server error occurred. Check PHP error logs.',
            'debug' => $error['message'] . ' in ' . $error['file'] . ':' . $error['line']
        ]);
    }
});

session_start();
require_once __DIR__ . '/../Connection/connection.php';
// Email configuration no longer needed - activation via popup instead

header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['firstname']) || !isset($data['lastname']) || !isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'error' => 'All fields are required']);
    exit;
}

$firstname = trim($data['firstname']);
$lastname = trim($data['lastname']);
$email = trim($data['email']);
$password = $data['password'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Invalid email format']);
    exit;
}

// Check if email already exists
$stmt = $con->prepare("SELECT user_id FROM LockIn_users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'error' => 'Email already registered']);
    exit;
}

// Hash password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Generate activation token (64 character random string)
$activationToken = bin2hex(random_bytes(32));

// Calculate expiration time (24 hours from now)
$tokenExpiresAt = date('Y-m-d H:i:s', strtotime('+24 hours'));

// Insert new user with activation token
$stmt = $con->prepare("INSERT INTO LockIn_users (firstname, lastname, email, password, is_activated, activation_token, token_expires_at) VALUES (?, ?, ?, ?, FALSE, ?, ?)");
$stmt->bind_param("ssssss", $firstname, $lastname, $email, $hashedPassword, $activationToken, $tokenExpiresAt);

if ($stmt->execute()) {
    $userId = $con->insert_id;
    
    // Account created successfully - return token for activation popup
    echo json_encode([
        'success' => true, 
        'message' => 'Registration successful! Please activate your account.',
        'activation_token' => $activationToken,
        'user_id' => $userId
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Registration failed. Please try again.']);
}

$stmt->close();
?>
