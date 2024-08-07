<?php

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

include 'config.php'; // Include the database connection file

$EncodedData = file_get_contents('php://input');
$DecodedData = json_decode($EncodedData, true);

if ($DecodedData) {
    $Name = mysqli_real_escape_string($CN, $DecodedData['Name']);
    $Mobile = mysqli_real_escape_string($CN, $DecodedData['Mobile']);
    $Salary = mysqli_real_escape_string($CN, $DecodedData['Salary']);
    $Password = mysqli_real_escape_string($CN, $DecodedData['Password']);
    $Image = $DecodedData['Image'];
    
    // Create the uploads directory if it doesn't exist
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Convert image from Base64
    $image_parts = explode(";base64,", $Image);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = $uploadDir . uniqid() . '.' . $image_type;

    // Save the image file
    file_put_contents($file, $image_base64);

    // Insert employee details into the employee table
    $IQ = "INSERT INTO employee (Name, Mobile, Salary, Image, Password) VALUES ('$Name', '$Mobile', '$Salary', '$file', '$Password')";
    $R = mysqli_query($CN, $IQ);

    if ($R) {
        $Message = "Employee has been registered successfully.";
    } else {
        $Message = "Server Error. Please try again: " . mysqli_error($CN);
    }

    $Response[] = array("Message" => $Message);
    echo json_encode($Response);
} else {
    $Response[] = array("Message" => "Invalid Input");
    echo json_encode($Response);
}

mysqli_close($CN); // Close the database connection

?>