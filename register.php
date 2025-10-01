<?php
session_start();
require_once 'db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = in_array($_POST['role'] ?? 'employee', ['employee','employer','admin']) ? $_POST['role'] : 'employee';

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Username or email taken.";
            $stmt->close();
        } else {
            $stmt->close();
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $ins = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $ins->bind_param('ssss', $username, $email, $hash, $role);
            if ($ins->execute()) {
                $_SESSION['flash'] = "Registration successful. Please login.";
                header('Location: login.php');
                exit;
            } else {
                $errors[] = "Registration error: " . $conn->error;
            }
            $ins->close();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .card {
      width: 100%;
      max-width: 480px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0px 10px 25px rgba(0, 0, 0, 0.25);
      padding: 35px 30px;
      animation: fadeIn 0.7s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(25px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .card h2 {
      font-weight: 800;
      font-size: 28px;
      margin-bottom: 20px;
      text-align: center;
      background: linear-gradient(45deg, #2563eb, #10b981);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .error {
      margin-bottom: 15px;
      padding: 10px;
      border-radius: 8px;
      font-size: 14px;
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: #b91c1c;
      text-align: center;
    }
    .input-group-text {
      background: #f3f4f6;
      border: none;
      border-radius: 10px 0 0 10px;
    }
    .form-control, select {
      border-radius: 0 10px 10px 0;
      padding: 12px;
      font-size: 15px;
      border: 1px solid #e5e7eb;
    }
    .form-control:focus, select:focus {
      border-color: #2563eb;
      box-shadow: 0 0 8px rgba(37, 99, 235, 0.25);
    }
    button[type="submit"] {
      background: linear-gradient(45deg, #2563eb, #10b981);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border-radius: 10px;
      width: 100%;
      transition: 0.3s ease;
      margin-top: 10px;
    }
    button[type="submit"]:hover {
      background: linear-gradient(45deg, #10b981, #2563eb);
      transform: translateY(-2px);
      box-shadow: 0 6px 14px rgba(37, 99, 235, 0.3);
    }
    .card p {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
      color: #374151;
    }
    .card p a {
      color: #2563eb;
      font-weight: 600;
      text-decoration: none;
      transition: 0.25s;
    }
    .card p a:hover {
      color: #10b981;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="card">
    <h2><i class="bi bi-person-plus"></i> Register</h2>
    <?php if ($errors): ?>
      <div class="error"><?= htmlspecialchars(implode('<br>', $errors)) ?></div>
    <?php endif; ?>

    <form method="post" action="register.php" required autocomplete="off">
      <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-person"></i></span>
        <input name="username" class="form-control" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
        <input name="email" class="form-control" placeholder="Email" type="email"  required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-lock"></i></span>
        <input name="password" class="form-control" placeholder="Password" type="password" required>
      </div>

      <div class="input-group mb-3">
        <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
        <select name="role" id="role" class="form-control" required>
          <option value="employee" <?= (($_POST['role'] ?? '') === 'employee') ? 'selected' : '' ?>>Employee</option>
          <option value="employer" <?= (($_POST['role'] ?? '') === 'employer') ? 'selected' : '' ?>>Employer</option>
          <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
        </select>
      </div>

      <button type="submit"><i class="bi bi-check-circle"></i> Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</body>
</html>
