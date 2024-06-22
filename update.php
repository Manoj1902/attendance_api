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

$Id = $DecodedData['Id'];
$Name = $DecodedData['Name'];
$Mobile = $DecodedData['Mobile'];
$Password = $DecodedData['Password'];

$UQ = "UPDATE employee SET Name='$Name', Mobile='$Mobile', Password='$Password' WHERE Id=$Id";

$R = mysqli_query($CN, $UQ);

if ($R) {
    $Message = "Employee details updated successfully";
} else {
    $Message = "Server error. Please try again";
}

$Response = array("Message" => $Message);
echo json_encode($Response);

mysqli_close($CN);

?>