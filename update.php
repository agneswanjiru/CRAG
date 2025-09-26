<?php 
include 'db.php'; 

// Get task by ID
$id = $_GET['id'];
$result = $conn->query("SELECT * FROM tasks WHERE id=$id");
$task = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head><title>Update Task</title></head>
<body>
    <h2>Update Task</h2>
    <form method="POST">
        <input type="text" name="title" value="<?php echo $task['title']; ?>" required><br><br>
        <textarea name="description"><?php echo $task['description']; ?></textarea><br><br>
        <select name="status">
            <option value="pending" <?php if($task['status']=='pending') echo 'selected'; ?>>Pending</option>
            <option value="in_progress" <?php if($task['status']=='in_progress') echo 'selected'; ?>>In Progress</option>
            <option value="completed" <?php if($task['status']=='completed') echo 'selected'; ?>>Completed</option>
        </select><br><br>
        <button type="submit" name="update">Update</button>
    </form>

    <?php
    if (isset($_POST['update'])) {
        $title = $_POST['title'];
        $desc = $_POST['description'];
        $status = $_POST['status'];

        $conn->query("UPDATE tasks SET title='$title', description='$desc', status='$status' WHERE id=$id");
        header("Location: index.php");
    }
    ?>
</body>
</html>