<?php include "db.php"; 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<! DOCTYPE html>
<html>
    <head>
        <title> Task Manager </title>
        <link rel = " stylesheet" href = "style.css">

    </head>
    <body>
        <h2>Task List</h2>
    <a href="add_task.php">+ Add New Task</a>
    <table border ="1" cellpadding="10">
        <tr>
            <th>ID</th><th>Title</th><th>Description</th><th>Status</th><th>Actions</th>
        </tr>
        <?php
        $user_id = $_SESSION['user_id'];
        $result = $conn->query("SELECT * FROM tasks WHERE user_id=$user_id ORDER BY created_at DESC");

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['title']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='update_task.php?id={$row['id']}'>Edit</a> | 
                        <a href='delete_task.php?id={$row['id']}'>Delete</a>
                    </td>
                  </tr>";
        }
        ?>
    </table>
    </body>
</html>