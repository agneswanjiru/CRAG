<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['verify_email'])) {
    header("Location: register.php");
    exit;
}

$email = $_SESSION['verify_email'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entered_otp = trim($_POST['otp']);

    $stmt = $conn->prepare("SELECT otp FROM users WHERE email = ? AND is_verified = 0 LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($db_otp);
        $stmt->fetch();
        if ($entered_otp === $db_otp) {
            $update = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL WHERE email = ?");
            $update->bind_param('s', $email);
            $update->execute();
            $update->close();

            unset($_SESSION['verify_email']);
            $_SESSION['flash'] = "Email verified successfully. You can now log in.";
            header('Location: login.php');
            exit;
        } else {
            $errors[] = "Invalid OTP. Please try again.";
        }
    } else {
        $errors[] = "Email not found or already verified.";
    }
    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Verify OTP</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="min-height:100vh;">
<div class="card p-4 shadow" style="max-width:400px;width:100%;">
  <h3 class="text-center mb-3">Verify Your Email</h3>
  <?php if ($errors): ?>
    <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label for="otp" class="form-label">Enter OTP sent to <?= htmlspecialchars($email) ?></label>
      <input type="text" name="otp" id="otp" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Verify</button>
  </form>
</div>
</body>
</html>
