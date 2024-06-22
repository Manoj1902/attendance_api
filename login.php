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

$Mobile = $DecodedData['Mobile'];
$Password = $DecodedData['Password'];

$SQ = "SELECT * FROM employee WHERE Mobile='$Mobile' AND Password='$Password'";

$Table = mysqli_query($CN, $SQ);

if (mysqli_num_rows($Table) > 0) {
    $Message = "Login Successful";
    $Status = true;
} else {
    $Message = "Invalid Mobile or Password";
    $Status = false;
}

$Response = array("Message" => $Message, "Status" => $Status);
echo json_encode($Response);

mysqli_close($CN);

?>