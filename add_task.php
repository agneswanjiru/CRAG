<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save'])) {
    $title = trim($_POST['title']);
    $desc  = trim($_POST['description']);
    $user_id = $_SESSION['user_id']; 
    $status = 'Pending';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Task</title>

   
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
            Add New Task
            </div>
            <div class="card-body">
                <form method="POST" class="p-3">
                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Enter task title" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Task Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" placeholder="Enter task details"></textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                        <button type="submit" name="save" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
