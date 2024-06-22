<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$CN = mysqli_connect("localhost", "root", "", "attendance");

if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    $query = "INSERT INTO uploads (image, date, time, location) VALUES ('$imgContent', '$date', '$time', '$location')";

    if (mysqli_query($CN, $query)) {
        echo json_encode(["message" => "Image uploaded successfully"]);
    } else {
        echo json_encode(["message" => "Failed to upload image"]);
    }
}

mysqli_close($CN);

?>