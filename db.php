<?php
$host = "localhost";
$user = "root";
$pass = "1234";
$db = "task_system";
$db = new mysqli($host, $user, $pass, $db);
if ($db->connect_error) {
    die("Database connection Failed: ". $db->connect_error);
}
?>