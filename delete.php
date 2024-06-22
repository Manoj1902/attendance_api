<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$CN = mysqli_connect("localhost", "root", "", "attendance");

if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}

$EncodedData = file_get_contents('php://input');
$DecodedData = json_decode($EncodedData, true);

$id = $DecodedData['id'];

$DQ = "DELETE FROM employee WHERE id = $id";

$R = mysqli_query($CN, $DQ);

if ($R) {
    $Message = "Employee has been Deleted Successfully";
} else {
    $Message = "Server Error. Please try again";
}

$Response = array("Message" => $Message);
echo json_encode($Response);

mysqli_close($CN);

?>