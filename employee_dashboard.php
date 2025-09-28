<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php'); exit;
}
$user_id = $_SESSION['user_id'];

// tasks assigned to this employee
$stmt = $conn->prepare("SELECT t.id,t.title,t.priority,t.deadline,t.status,u.username AS assigned_by_name,t.created_at FROM tasks t LEFT JOIN users u ON t.assigned_by = u.id WHERE t.assigned_to = ? ORDER BY t.created_at DESC");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$tasks = $stmt->get_result();

// get availability
$av = $conn->prepare("SELECT availability FROM users WHERE id = ?");
$av->bind_param('i', $user_id);
$av->execute();
$av->bind_result($availability);
$av->fetch();
$av->close();
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Employee Dashboard</title><link rel="stylesheet" href="style.css"></head>
<body>
  <div class="topbar">
    <div>Logged in as <?= htmlspecialchars($_SESSION['username']) ?> (Employee)</div>
    <div><a href="logout.php">Logout</a></div>
  </div>

  <div class="container">
    <div class="card">
      <h3>Update Availability</h3>
      <form method="post" action="update_availability.php">
        <select name="status">
          <option value="available" <?= $availability === 'available' ? 'selected' : '' ?>>Available</option>
          <option value="busy" <?= $availability === 'busy' ? 'selected' : '' ?>>Busy</option>
          <option value="absent" <?= $availability === 'absent' ? 'selected' : '' ?>>Absent</option>
        </select>
        <button type="submit">Update</button>
      </form>
    </div>

    <div class="card">
      <h3>My Tasks</h3>
      <?php if ($tasks->num_rows === 0): ?>
        <p>No tasks assigned to you.</p>
      <?php else: ?>
        <table>
          <thead><tr><th>ID</th><th>Title</th><th>Priority</th><th>Deadline</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php while ($t = $tasks->fetch_assoc()): ?>
            <tr>
              <td><?= $t['id'] ?></td>
              <td><?= htmlspecialchars($t['title']) ?></td>
              <td><?= $t['priority'] ?></td>
              <td><?= $t['deadline'] ?? '—' ?></td>
              <td><?= $t['status'] ?></td>
              <td>
                <a href="view_task.php?id=<?= $t['id'] ?>">View</a>
                <?php if ($t['status'] !== 'completed'): ?> | <a href="complete_task.php?id=<?= $t['id'] ?>" onclick="return confirm('Mark completed?')">Mark Completed</a><?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body></html>
