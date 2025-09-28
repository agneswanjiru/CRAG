<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') { header('Location: login.php'); exit; }
$user_id = $_SESSION['user_id'];
$status = ($_POST['status'] ?? '') === 'absent' ? 'absent' : (($_POST['status'] ?? '') === 'busy' ? 'busy' : 'available');
$stmt = $conn->prepare("UPDATE users SET availability = ? WHERE id = ?");
$stmt->bind_param('si', $status, $user_id);
$stmt->execute();
$stmt->close();
// Optionally record in history table as action (not implemented to keep simple)
header('Location: employee_dashboard.php');
exit;
