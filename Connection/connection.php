<?php
/*
// connecting to the database
$host="localhost";
$user="root";
$pass="";
$db="attendancemanagement";

$con=new mysqli($host,$user,$pass,$db);
//needed valraiables (hostname,host_user,hostpassword,databasename)

if($con->connect_error){
    // error logic
    // die("Connection falied");
    $errorjson=["state"=>false];
    echo json_encode($errorjson);

    die();
}else{
    // success logic
    //echo "Connected successfully";
    
}
?>
*/


// Alternative approach using environment file for better security and flexibility
$envFile = __DIR__ . '/../env/connect.env';
$env = parse_ini_file($envFile);

// Debug: Log if env file doesn't exist or fails to parse
if ($env === false) {
    error_log("Failed to parse env file: $envFile");
}

// Check if env file was parsed successfully
if ($env === false) {
    
    // If we're in an API context, return JSON error
    if (!headers_sent()) {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to load database configuration']);
    } else {
        die('Failed to load database configuration');
    }
    exit;
}


$con = new mysqli(
    $env['host'],
    $env['user'],
    $env['password'],
    $env['database']
);

// Check connection
if ($con->connect_error) {
die("Connection failed: " . $con->connect_error);
}
?>
