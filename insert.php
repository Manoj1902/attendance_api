<?php

// CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$CN = mysqli_connect ("localhost", "root", "");
$DB = mysqli_select_db($CN, 'attendance');

$EncodedData = file_get_contents('php://input');
$DecodedData = json_decode($EncodedData, true);


// $Name = $DecodedData['Name'];
// $Mobile = $DecodedData['Mobile'];
// $Password = $DecodedData['Password'];

$Name = mysqli_real_escape_string($CN, $DecodedData['Name']);
$Mobile = mysqli_real_escape_string($CN, $DecodedData['Mobile']);
$Password = mysqli_real_escape_string($CN, $DecodedData['Password']);

$IQ = "insert into employee (Name, Mobile, Password) values ('$Name', '$Mobile', '$Password')";


$R = mysqli_query($CN, $IQ);

if($R){
    // $Message = "Employee has been Registered Successfully";

 // Sanitize the employee name for use as a table name
 $SanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $Name);

     // Create a new table for the employee
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
        // Insert the employee's name into the new table
        $InsertName = "INSERT INTO `" . $SanitizedName . "` (name, attendance, attendance_date, attendance_time, location, image) VALUES ('$SanitizedName', '', '', '', '', '')";
        
        if (mysqli_query($CN, $InsertName)) {
            $Message = "Employee has been Registered Successfully and Table created.";
        } else {
            $Message = "Employee registered and table created, but there was an error inserting the name: " . mysqli_error($CN);
        }
    } else {
        $Message = "Employee registered, but there was an error creating the table: " . mysqli_error($CN);
    }
}
else{
    
    $Message = "Server Error. Please try again";
}

$Response[] = array("Message"=>$Message);
echo json_encode($Response);


?>