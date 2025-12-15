<?php
/**
 * Signup Handler
 * 
 * Why: This file handles new user registration by:
 * 1. Receiving JSON signup data
 * 2. Validating all input fields
 * 3. Checking if email already exists
 * 4. Hashing password securely
 * 5. Inserting user into database
 * 6. Setting session variables
 * 7. Returning success/error response
 */

session_start();

require 'db.php';

// Set JSON response header
header('Content-Type: application/json');

// Get JSON input from request body
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
$input = json_decode($rawInput, true);

// Check if JSON decoding failed
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid data format. Please try again."
    ]);
    exit();
}

// Validate required fields exist
if (!isset($input['firstname']) || !isset($input['lastname']) || 
    !isset($input['email']) || !isset($input['password']) || 
    !isset($input['confirm_password'])) {
    echo json_encode([
        "success" => false,
        "message" => "All fields are required."
    ]);
    exit();
}

// Sanitize and get input values
// Why: trim() removes whitespace, filter_var() sanitizes email
// Alternative: Could use htmlspecialchars() for additional XSS protection
$firstname = trim($input['firstname']);
$lastname = trim($input['lastname']);
$email = filter_var(trim($input['email']), FILTER_SANITIZE_EMAIL);
$password = $input['password'];
$confirm_password = $input['confirm_password'];

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);
    exit();
}

// Validate password match
if ($password !== $confirm_password) {
    echo json_encode([
        "success" => false,
        "message" => "Passwords do not match."
    ]);
    exit();
}

// Validate password length (should match frontend validation)
if (strlen($password) < 8) {
    echo json_encode([
        "success" => false,
        "message" => "Password must be at least 8 characters long."
    ]);
    exit();
}

// Validate name lengths
if (strlen($firstname) < 2 || strlen($lastname) < 2) {
    echo json_encode([
        "success" => false,
        "message" => "First and last name must be at least 2 characters long."
    ]);
    exit();
}

// Check if email already exists
// Why: Prevent duplicate accounts with same email
$check_stmt = $con->prepare("SELECT user_id FROM users WHERE email = ?");
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    echo json_encode([
        "success" => false,
        "message" => "An account with this email already exists."
    ]);
    $check_stmt->close();
    exit();
}
$check_stmt->close();

// Hash password securely
// Why: password_hash() uses bcrypt algorithm, automatically salts passwords
// This is the secure way to store passwords - never store plain text!
// Alternative: Could use PASSWORD_ARGON2ID for even stronger hashing (PHP 7.2+)
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insert new user into database
// Why: Prepared statements prevent SQL injection
// Alternative: Could use PDO instead of mysqli for different syntax
$insert_stmt = $con->prepare("INSERT INTO users (first_name, last_name, email, password_hash) VALUES (?, ?, ?, ?)");
$insert_stmt->bind_param("ssss", $firstname, $lastname, $email, $password_hash);

if ($insert_stmt->execute()) {
    // Get the newly created user_id
    // Why: We need user_id for session and future database operations
    $user_id = $con->insert_id;
    
    // Set session variables
    // Why: Sessions store user data server-side, more secure than cookies for sensitive data
    // Alternative: Could use JWT tokens for stateless authentication (better for APIs/mobile)
    $_SESSION['user_id'] = $user_id;
    $_SESSION['first_name'] = $firstname;
    $_SESSION['last_name'] = $lastname;
    $_SESSION['email'] = $email;
    
    $insert_stmt->close();
    
    // Return success response with user info
    // Why: Frontend needs user data to personalize the experience
    echo json_encode([
        "success" => true,
        "message" => "Account created successfully!",
        "user" => [
            "id" => $user_id,
            "name" => $firstname . " " . $lastname
        ]
    ]);
} else {
    // Database error
    // Why: Log errors in production, don't expose database details
    // Alternative: Could use error_log() to log detailed errors server-side
    echo json_encode([
        "success" => false,
        "message" => "Registration failed. Please try again."
    ]);
    $insert_stmt->close();
}

$con->close();
?>

