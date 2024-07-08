<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the raw POST data
$post_data = file_get_contents("php://input");
$request = json_decode($post_data, true);

$mobile = $request['mobile'];
$password = $request['password'];

error_log("Received login request for mobile: $mobile");

// Query to check credentials
$sql = "SELECT * FROM employee WHERE Mobile='$mobile' AND Password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(array("status" => "success", "employee" => $row));
} else {
    echo json_encode(array("status" => "error", "message" => "Invalid credentials"));
}

$conn->close();
?>