<?php
$servername = "localhost";
$username = "root"; // your MySQL username
$password = ""; // your MySQL password
$dbname = "attendance";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$mobile = $_POST['mobile'];
$attendance = $_POST['attendance'];
$attendance_date = $_POST['attendance_date'];
$attendance_time = $_POST['attendance_time'];
$location = $_POST['location'];

$image = $_FILES['image']['name'];
$target_dir = "uploads/";
$target_file = $target_dir . basename($image);
move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

$sql = "INSERT INTO uploads (Name, Mobile, attendance, Attendance_date, Attendance_time, Location, Image) VALUES ('$name', '$mobile', '$attendance', '$attendance_date', '$attendance_time', '$location', '$target_file')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("status" => "success", "message" => "Attendance recorded successfully"));
} else {
    echo json_encode(array("status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error));
}

$conn->close();
?>