<?php 
include "db.php"; 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Task Manager</a>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-secondary">Task List</h2>
            <a href="add_task.php" class="btn btn-primary">+ Add New Task</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $result = $conn->query("SELECT * FROM tasks WHERE user_id=$user_id ORDER BY created_at DESC");

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "
                                <tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['title']}</td>
                                    <td>{$row['description']}</td>
                                    <td>
                                        <span class='badge bg-" . 
                                            ($row['status'] == 'Completed' ? 'success' : 'warning') . 
                                        "'>{$row['status']}</span>
                                    </td>
                                    <td class='text-center'>
                                        <a href='update_task.php?id={$row['id']}' class='btn btn-sm btn-outline-primary me-1'>Edit</a>
                                        <a href='delete_task.php?id={$row['id']}' class='btn btn-sm btn-outline-danger'>Delete</a>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center text-muted py-3'>No tasks found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
