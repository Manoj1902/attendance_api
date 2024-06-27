<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection--
$CN = mysqli_connect("localhost", "root", "", "attendance");

if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}

// Server URL (modify this to your server URL)
$server_url = 'http://192.168.137.1/uploads/';

// SQL query to fetch all details from the 'employee' table
$IQ = "SELECT * FROM employee";
$R = mysqli_query($CN, $IQ);

$Response = array();

// Fetch data from the database
while ($Row = mysqli_fetch_assoc($R)) {
    $Row['Image'] = $server_url . $Row['Image']; // Append the server URL to the image path
    $Response[] = $Row;
}

// Encode the response as JSON
echo json_encode($Response);

// Close the database connection
mysqli_close($CN);

?>