<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if a task ID was provided
if (!isset($_GET['id'])) {
    echo "No task ID provided.";
    exit;
}

$task_id = intval($_GET['id']);

// Fetch task details
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

// Handle form submission
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Task</title>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">Task Manager</a>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

  
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">
            Edit Task
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control" 
                               value="<?php echo htmlspecialchars($task['title']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Task Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" required><?php echo htmlspecialchars($task['description']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="Pending" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
