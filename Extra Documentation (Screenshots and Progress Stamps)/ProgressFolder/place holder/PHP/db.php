<?php
/**
 * Database Connection File
 * 
 * Why: Centralizing database connection makes it easy to update credentials
 * and ensures consistent connection handling across all PHP files
 */

// Database configuration
// TODO: Update these with your actual database credentials
$host = 'localhost';
$username = 'root';  // Change this to your MySQL username
$password = '';      // Change this to your MySQL password (usually empty for XAMPP)
$database = 'lockin_db'; // Change this to your database name

// Create connection using mysqli (more secure than mysql_* functions)
$con = new mysqli($host, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    // In production, don't expose error details - log them instead
    die(json_encode([
        "success" => false,
        "message" => "Database connection failed. Please try again later."
    ]));
}

// Set charset to utf8mb4 to support all characters including emojis
$con->set_charset("utf8mb4");

?>

