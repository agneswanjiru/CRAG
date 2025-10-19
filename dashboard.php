<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employer') {
    header('Location: login.php'); exit;
}
$employer_id = $_SESSION['user_id'];

// employees list (available first)
$emps = $conn->prepare("SELECT id,username,availability FROM users WHERE role='employee' ORDER BY availability DESC, username ASC");
$emps->execute();
$emps_res = $emps->get_result();

// tasks assigned by this employer
$tasks = $conn->prepare(
  "SELECT t.id,t.title,t.priority,t.deadline,t.status,u.username AS assigned_to_name,t.created_at
   FROM tasks t LEFT JOIN users u ON t.assigned_to = u.id
   WHERE t.assigned_by = ?
   ORDER BY t.created_at DESC"
);
$tasks->bind_param('i', $employer_id);
$tasks->execute();
$tasks_res = $tasks->get_result();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Employer Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
  <div class="topbar">
    <div>Logged in as <?= htmlspecialchars($_SESSION['username']) ?> (Employer)</div>
    <div><a href="logout.php">Logout</a></div>
  </div>

  <div class="container">
    <div class="card">
      <h3>Assign New Task</h3>
      <form method="post" action="add_task.php">
        <input name="title" placeholder="Task title" required>
        <textarea name="description" placeholder="Task description"></textarea>
        <label>Priority</label>
        <select name="priority">
          <option value="low">Low</option>
          <option value="medium" selected>Medium</option>
          <option value="high">High</option>
        </select>
        <label>Deadline (optional)</label>
        <input type="date" name="deadline">
        <label>Assign to</label>
        <select name="employee_id" required>
          <option value="">-- choose employee --</option>
          <?php while ($e = $emps_res->fetch_assoc()): ?>
            <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['username']) ?> (<?= $e['availability'] ?>)</option>
          <?php endwhile; ?>
        </select>
        <button type="submit" name="assign_task">Assign Task</button>
      </form>
    </div>

    <div class="card">
      <h3>Tasks You Assigned</h3>
      <?php if ($tasks_res->num_rows === 0): ?>
        <p>No tasks yet.</p>
      <?php else: ?>
        <table>
          <thead><tr><th>ID</th><th>Title</th><th>Assigned To</th><th>Priority</th><th>Deadline</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php while ($r = $tasks_res->fetch_assoc()): ?>
            <tr>
              <td><?= $r['id'] ?></td>
              <td><?= htmlspecialchars($r['title']) ?></td>
              <td><?= htmlspecialchars($r['assigned_to_name'] ?? '—') ?></td>
              <td><?= $r['priority'] ?></td>
              <td><?= $r['deadline'] ?? '—' ?></td>
              <td><?= $r['status'] ?></td>
              <td>
                <a href="view_task.php?id=<?= $r['id'] ?>">View</a> |
                <a href="reassign_task.php?id=<?= $r['id'] ?>">Reassign</a> |
                <a href="delete_task.php?id=<?= $r['id'] ?>" onclick="return confirm('Delete task?')">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body></html>
