<?php
$host = "localhost";
$user = "root";
$pass = "1224";
$dbname = "taskapp";

// Create mysqli connection as $conn
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection Failed: " . $conn->connect_error);
}
?>
 