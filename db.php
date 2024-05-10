
<?php
//filename: db.php
// Database configuration
include 'content-security-policy.php';
$dbHost = 'localhost';
$dbUsername = 'admin';
$dbPassword = 'admin';
$dbName = 'student';

// Create database connection
$mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
