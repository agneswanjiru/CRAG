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
<html><head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="style.css"></head>
<body>
  <div class="card">
    <h2>Register</h2>
    <?php if ($errors): ?><div class="error"><?= htmlspecialchars(implode('<br>', $errors)) ?></div><?php endif; ?>
    <form method="post" action="register.php" autocomplete="off">
      <input name="username" placeholder="Username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
      <input name="email" placeholder="Email" type="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <input name="password" placeholder="Password" type="password" required>
      <label>Role</label>
      <select name="role" required>
        <option value="employee" <?= (($_POST['role'] ?? '') === 'employee') ? 'selected' : '' ?>>Employee</option>
        <option value="employer" <?= (($_POST['role'] ?? '') === 'employer') ? 'selected' : '' ?>>Employer</option>
        <option value="admin" <?= (($_POST['role'] ?? '') === 'admin') ? 'selected' : '' ?>>Admin</option>
      </select>
      <button type="submit">Register</button>
    </form>
    <p>Have account? <a href="login.php">Login</a></p>
  </div>
</body></html>
