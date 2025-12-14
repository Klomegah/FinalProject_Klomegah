<?php
session_start();
require_once __DIR__ . '/../Connection/connection.php';
header('Content-Type: application/json');

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Get JSON data from request
$data = json_decode(file_get_contents('php://input'), true);

// Validate required fields
if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['success' => false, 'error' => 'Email and password are required']);
    exit;
}

$email = trim($data['email']);
$password = $data['password'];

// Find user by email (including activation status)
$stmt = $con->prepare("SELECT user_id, firstname, lastname, email, password, is_activated FROM LockIn_users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Invalid email or password']);
    exit;
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid email or password']);
    exit;
}

// Check if account is activated
if (!$user['is_activated']) {
    echo json_encode([
        'success' => false, 
        'error' => 'Please activate your account first. Check your email for the activation link.',
        'not_activated' => true
    ]);
    exit;
}

// Create session
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['email'] = $user['email'];
$_SESSION['firstname'] = $user['firstname'];
$_SESSION['lastname'] = $user['lastname'];

echo json_encode([
    'success' => true,
    'message' => 'Login successful',
    'user' => [
        'user_id' => $user['user_id'],
        'firstname' => $user['firstname'],
        'lastname' => $user['lastname'],
        'email' => $user['email']
    ]
]);

$stmt->close();
?>
