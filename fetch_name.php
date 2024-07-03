<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection
$CN = mysqli_connect("localhost", "root", "", "attendance");

if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}

// Server URL (modify this to your server URL)
$server_url = 'http://192.168.137.1/api/';

// Get the mobile number from the request
$mobile = $_POST['Mobile'];
$SanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $employeeName);

$IQ = "SELECT name FROM employee WHERE Mobile = '$mobile'";
$R = mysqli_query($CN, $IQ);

$Response = array();

if ($Row = mysqli_fetch_assoc($R)) {
    // Only prepend the server URL if it's not already present
    if (strpos($Row['Image'], 'uploads/') !== false) {
        $Row['Image'] = $server_url . $Row['Image'];
    }
    $Response = $Row;
} else {
    $Response = array("Message" => "Employee not found");
}

// Encode the response as JSON
echo json_encode($Response, JSON_PRETTY_PRINT);

// Close the database connection
mysqli_close($CN);
die();

?>