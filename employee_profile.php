<?php
session_start();
require_once 'db.php';

// Ensure only employees can access this page
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$msg = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $specialty = trim($_POST['specialty']);

    $stmt = $conn->prepare("UPDATE users SET username = ?, specialty = ? WHERE id = ?");
    $stmt->bind_param("ssi", $username, $specialty, $user_id);
    $stmt->execute();

    $_SESSION['username'] = $username;
    $msg = "Profile updated successfully!";
}

// Fetch existing data
$stmt = $conn->prepare("SELECT username, specialty FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Employee Profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .profile-card {
      max-width: 500px;
      margin: 60px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
      padding: 30px;
    }
    input.form-control {
      background-color: #fff;
      border: 1px solid #ced4da;
      color: #212529;
    }
    input.form-control:focus {
      border-color: #0d6efd;
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }
    .btn-custom {
      background-color: #0d6efd;
      border: none;
      color: white;
    }
    .btn-custom:hover {
      background-color: #0b5ed7;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="profile-card">
      <h3 class="text-center mb-4">Complete Your Profile</h3>

      <?php if (!empty($msg)) : ?>
        <div class="alert alert-success text-center"><?= htmlspecialchars($msg) ?></div>
      <?php endif; ?>

      <form method="post">
        <div class="mb-3">
          <label class="form-label fw-semibold">Full Name</label>
          <input type="text" name="username" class="form-control" 
                 placeholder="Enter your full name"
                 value="<?= isset($user['username']) && $user['username'] !== null ? htmlspecialchars($user['username']) : '' ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Job Specialty</label>
          <input type="text" name="specialty" class="form-control"
                 value="<?= isset($user['specialty']) && $user['specialty'] !== null ? htmlspecialchars($user['specialty']) : '' ?>" required>
        </div>

        <button type="submit" class="btn btn-custom w-100">Save Profile</button>
      </form>

      <div class="text-center mt-3">
        <a href="logout.php" class="text-danger">Logout</a>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
