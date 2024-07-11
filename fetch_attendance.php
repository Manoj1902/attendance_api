<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'config.php'; // Include the database connection file

$EncodedData = file_get_contents('php://input');
$DecodedData = json_decode($EncodedData, true);

if ($DecodedData) {
    $Mobile = mysqli_real_escape_string($CN, $DecodedData['Mobile']);
    $Month = mysqli_real_escape_string($CN, $DecodedData['Month']);
    $Year = mysqli_real_escape_string($CN, $DecodedData['Year']);

    $startDate = "$Year-$Month-01";
    $endDate = date("Y-m-t", strtotime($startDate)); // Get the last day of the month

    $attendanceQuery = "SELECT * FROM uploads WHERE Mobile = '$Mobile' AND attendance_date BETWEEN '$startDate' AND '$endDate' ORDER BY attendance_date DESC";
    $attendanceResult = mysqli_query($CN, $attendanceQuery);

    $attendanceDetails = array();
    while ($Row = mysqli_fetch_assoc($attendanceResult)) {
        $attendanceDetails[] = $Row;
    }

    echo json_encode($attendanceDetails, JSON_PRETTY_PRINT);
} else {
    echo json_encode(["message" => "Invalid Input"]);
}

mysqli_close($CN); // Close the database connection

?>