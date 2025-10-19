<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];


if (!isset($_GET['id'])) {
    echo "No task ID provided.";
    exit;
}

$task_id = intval($_GET['id']);

// This is to fetch the task details from the database
$stmt = $conn->prepare("SELECT title, description, status FROM tasks WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $task_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Task not found.";
    exit;
}

$task = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    if (empty($title) || empty($description) || empty($status)) {
        echo "All fields are required.";
    } else {
        $update = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?");
        $update->bind_param("sssii", $title, $description, $status, $task_id, $user_id);

        if ($update->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Error updating task: " . $update->error;
        }

        $update->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Task</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Edit Task</h2>

    <form method="POST" action="">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" rows="4" cols="40" required><?php echo htmlspecialchars($task['description']); ?></textarea><br><br>

        <label>Status:</label><br>
        <select name="status" required>
            <option value="pending" <?php if ($task['status'] == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
        </select><br><br>

        <button type="submit">Update Task</button>
    </form>

    <br>
    <a href="index.php">Back to Task List</a>
</body>
</html>
