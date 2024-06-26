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

$CN = mysqli_connect("localhost", "root", "");
$DB = mysqli_select_db($CN, 'attendance');

$EncodedData = file_get_contents('php://input');
$DecodedData = json_decode($EncodedData, true);

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
$ImagePath = $uploadDir . uniqid() . '.jpg';
file_put_contents($ImagePath, base64_decode($Image));

$IQ = "INSERT INTO employee (Name, Mobile, Salary, Image, Password) VALUES ('$Name', '$Mobile', '$Salary', '$ImagePath', '$Password')";

$R = mysqli_query($CN, $IQ);

if ($R) {
    $SanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $Name);

    $EmployeeTable = "CREATE TABLE `" . $SanitizedName . "` (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        attendance VARCHAR(10) NOT NULL,
        attendance_date DATE NOT NULL,
        attendance_time TIME NOT NULL,
        location VARCHAR(255) NOT NULL,
        image LONGBLOB NOT NULL
    )";

    if (mysqli_query($CN, $EmployeeTable)) {
        $InsertName = "INSERT INTO `" . $SanitizedName . "` (name, attendance, attendance_date, attendance_time, location, image) VALUES ('$SanitizedName', '', '', '', '', '')";

        if (mysqli_query($CN, $InsertName)) {
            $Message = "Employee has been Registered Successfully and Table created.";
        } else {
            $Message = "Employee registered and table created, but there was an error inserting the name: " . mysqli_error($CN);
        }
    } else {
        $Message = "Employee registered, but there was an error creating the table: " . mysqli_error($CN);
    }
} else {
    $Message = "Server Error. Please try again: " . mysqli_error($CN);
}

$Response[] = array("Message" => $Message);
echo json_encode($Response);

?>