<?php
session_start();
include 'db.php';

// This checks if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Checks if a task ID is provided in the URL
if (isset($_GET['id'])) {
    $task_id = intval($_GET['id']);
    $user_id = $_SESSION['user_id']; // This ensure a user can delete only their own tasks

    
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $task_id, $user_id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting task: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "No task ID provided.";
}
?>
