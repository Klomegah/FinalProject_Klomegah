<?php

// Authentication check 
session_start();

if (!isset($_SESSION['user_id'])) {
    // If it's an AJAX/API request, return JSON response
    
    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    $wantsJson = !empty($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    
    if ($isAjax || $wantsJson) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        exit;
    }
    // Otherwise redirect to login page
    header("Location: ../LoginAndSignUpPages/login-html.php");
    exit;
}
?>
