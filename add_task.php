<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Add Task</title></head>
<body>
    <h2>Add Task</h2>
    <form method="POST">
        <input type="text" name="title" placeholder="Task title" required><br><br>
        <textarea name="description" placeholder="Task description"></textarea><br><br>
        <button type="submit" name="save">Save</button>
    </form>

    <?php
    if (isset($_POST['save'])) {
        $title = $_POST['title'];
        $desc  = $_POST['description'];
        $user_id = $_SESSION['user_id']; // get logged in user
        $conn->query("INSERT INTO tasks (title, description, user_id) VALUES ('$title', '$desc', '$user_id')");

        header("Location: index.php");
    }
    ?>
</body>
</html>
