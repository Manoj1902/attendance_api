<?php

// CORS headers
header("Access-Control-Allow-Origin: *"); // Replace with your domain or '*'
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$CN = mysqli_connect("localhost", "root", "", "attendance");

if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $_FILES['image']['tmp_name'];
    $imgContent = addslashes(file_get_contents($image));
    $attendance = $_POST['attendance'];
    $date = $_POST['attendance_date'];
    $time = $_POST['attendance_time'];
    $location = $_POST['location'];
    $employeeName = $_POST['name'];  // Get the employee name
    $employeeMobile = $_POST['mobile'];  // Get the employee mobile number

    // Sanitize the table name to avoid SQL injection
    $SanitizedName = preg_replace('/[^a-zA-Z0-9_]/', '_', $employeeName);

    // Fetch the employee details from the employee's table
    $employeeQuery = "SELECT * FROM `$SanitizedName` WHERE mobile='$employeeMobile'";
    $employeeResult = mysqli_query($CN, $employeeQuery);

    if ($employeeResult && mysqli_num_rows($employeeResult) > 0) {
        // Insert attendance details into the employee's table
        $insertQuery = "INSERT INTO `$SanitizedName` (attendance, attendance_date, attendance_time, location, image) VALUES ('present', '$date', '$time', '$location', '$imgContent')";
        
        if (mysqli_query($CN, $insertQuery)) {
            echo json_encode(["message" => "Attendance recorded successfully"]);
        } else {
            echo json_encode(["message" => "Failed to record attendance"]);
        }
    } else {
        echo json_encode(["message" => "Employee not found"]);
    }
}

mysqli_close($CN);

?>