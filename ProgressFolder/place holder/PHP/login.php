<?php 
/**
 * Login Handler
 * 
 * Why: This file handles user authentication by:
 * 1. Receiving JSON login credentials
 * 2. Validating the input
 * 3. Checking database for user
 * 4. Verifying password
 * 5. Setting session variables
 * 6. Returning success/error response
 */

session_start();

require 'db.php';

// Set JSON response header - tells browser to expect JSON
header('Content-Type: application/json');

// Get JSON input from request body
// Why: file_get_contents("php://input") reads raw POST data
$rawInput = file_get_contents("php://input");

// Check if input is empty
if (empty($rawInput)) {
    echo json_encode([
        "success" => false,
        "message" => "No data received. Please fill in all fields."
    ]);
    exit();
}

// Decode JSON input
// Why: json_decode converts JSON string to PHP array
// The 'true' parameter makes it return associative array instead of object
$input = json_decode($rawInput, true);

// Check if JSON decoding failed
// Why: json_last_error() checks if JSON was valid
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid data format. Please try again."
    ]);
    exit();
}

// Validate required fields exist
// Why: isset() checks if array keys exist before accessing them
if (!isset($input['email']) || !isset($input['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Email and password are required."
    ]);
    exit();
}

// Sanitize and get email and password from input
// Why: trim() removes whitespace, filter_var() validates email format
$email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
$password = $input['password']; // Don't sanitize password - we need it as-is for verification

// Additional email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);
    exit();
}

// Find user by email using prepared statement
// Why: Prepared statements prevent SQL injection attacks
// Alternative approach: Could also use PDO instead of mysqli for different syntax
$stmt = $con->prepare("SELECT user_id, first_name, last_name, email, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email); // "s" indicates the type is string
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // No user found with that email
    // Why: Generic message for security (doesn't reveal if email exists)
    // Alternative: Could use "Invalid email or password" to be more secure
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password"
    ]);
    $stmt->close();
    exit();
}

$user = $result->fetch_assoc();
$stmt->close();

// Verify password
// Why: password_verify() securely compares plain password with hashed password
// Alternative: Could use hash_equals() for timing attack protection, but password_verify handles this
if (password_verify($password, $user['password_hash'])) {
    // Password is correct, set session variables
    // Why: Sessions store user data server-side, more secure than cookies for sensitive data
    // Alternative: Could use JWT tokens for stateless authentication (better for APIs)
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['email'] = $user['email'];
    
    // Return success response
    // Why: Frontend needs to know login succeeded to redirect
    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "user" => [
            "id" => $user['user_id'],
            "name" => $user['first_name'] . " " . $user['last_name']
        ]
    ]);
} else {
    // Password is incorrect
    // Why: Generic message prevents email enumeration attacks
    echo json_encode([
        "success" => false,
        "message" => "Invalid email or password"
    ]);
    exit();
}

$con->close();
?>

