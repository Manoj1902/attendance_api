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

$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// SQL query to fetch all details from the 'employee' table
$IQ = "SELECT * FROM employee";
$R = mysqli_query($CN, $IQ);

$Response = array();

// Fetch data from the database
while ($Row = mysqli_fetch_assoc($R)) {
    // Only prepend the server URL if it's not already present
    if (strpos($Row['Image'], 'uploads/') !== false) {
        $Row['Image'] = $server_url . $Row['Image'];
    }

    // Fetch attendance details for the employee for the specified month and year
    $Mobile = $Row['Mobile'];
    $attendanceQuery = "SELECT * FROM uploads WHERE Mobile = '$Mobile' AND MONTH(Attendance_date) = '$month' AND YEAR(Attendance_date) = '$year' ORDER BY Attendance_date DESC";
    $attendanceResult = mysqli_query($CN, $attendanceQuery);

    $attendanceDetails = array();
    while ($attendanceRow = mysqli_fetch_assoc($attendanceResult)) {
        $attendanceDetails[] = $attendanceRow;
    }

    $Row['AttendanceDetails'] = $attendanceDetails;
    $Response[] = $Row;
}

// Encode the response as JSON
echo json_encode($Response, JSON_PRETTY_PRINT);

// Close the database connection
mysqli_close($CN);
die();

?>