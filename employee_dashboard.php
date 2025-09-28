<?php
include 'db.php';
session_start();

if ($_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];
?>

<h2>Employee Dashboard</h2>
<a href="logout.php">Logout</a>

<h3>Update Availability</h3>
<form method="POST" action="update_availability.php">
    <select name="status">
        <option value="available">Available</option>
        <option value="absent">Absent</option>
    </select>
    <button type="submit">Update</button>
</form>

<h3>My Tasks</h3>
<ul>
<?php
$tasks = $conn->query("SELECT * FROM tasks WHERE assigned_to=$user_id");
while ($t = $tasks->fetch_assoc()) {
    echo "<li>{$t['title']} - {$t['status']} ";
    if ($t['status'] !== 'completed') {
        echo "<a href='complete_task.php?id={$t['id']}'>Mark Completed</a>";
    }
    echo "</li>";
}
?>
</ul>
