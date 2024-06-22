<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$CN = mysqli_connect("localhost", "root", "", "attendance");

if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}

$SQ = "SELECT id, image, date, time, location FROM uploads";
$Table = mysqli_query($CN, $SQ);

$Response = array();

while ($Row = mysqli_fetch_assoc($Table)) {
    $Response[] = array(
        "id" => $Row['id'],
        "image" => base64_encode($Row['image']),
        "date" => $Row['date'],
        "time" => $Row['time'],
        "location" => $Row['location']
    );
}

echo json_encode($Response);

mysqli_close($CN);

?>