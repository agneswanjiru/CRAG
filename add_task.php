<?php
// Start the session before any output
session_start();
include 'db.php';

// Checks if a user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");
    exit();
}

if (isset($_POST['save'])) {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $user_id = $_SESSION['user_id']; 


    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO tasks (user_id, title, description, status, created_at) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("isss", $user_id, $title, $desc, $status);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error adding task: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Add Task</h2>

    <form method="POST">
        <input type="text" name="title" placeholder="Task title" required><br>
        <textarea name="description" placeholder="Task description"></textarea><br>
        <button type="submit" name="save">Save</button>
    </form>
</body>
</html>
