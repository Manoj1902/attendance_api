<?php
// Database connection settings
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'attendance';

// Create connection
$CN = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Check connection
if (!$CN) {
    die("Connection failed: " . mysqli_connect_error());
}
?>