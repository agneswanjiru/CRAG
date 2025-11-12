<?php 
include 'db.php';
session_start();

$errormsg = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

  
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=? LIMIT 1");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

    if ($user['role'] === 'employer' || $user['role'] === 'admin') {
    header("Location: index.php"); 
    exit;
} elseif ($user['role'] === 'employee') {
    header("Location: employee_profile.php");
    exit;
} else {
    $errormsg = "Unknown user role!";
}



        } else {
            $errormsg = "Invalid password!";
        }
    } else {
        $errormsg = "Invalid password or role mismatch!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #814c4cff 0%, #022839ff 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-form {
      width: 100%;
      max-width: 380px;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      border-radius: 16px;
      box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
      padding: 30px;
      animation: fadeIn 0.7s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .login-form h2 {
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
      text-align: center;
    }

    .login-form .hint-text {
      text-align: center;
      font-size: 14px;
      color: #777;
      margin-bottom: 20px;
    }

    .form-control {
      border-radius: 8px;
      padding: 12px;
      font-size: 14px;
    }

    .form-control:focus {
      border-color: #4facfe;
      box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
    }

    .btn-custom {
      background: linear-gradient(45deg, #4facfe, #081a3dff);
      border: none;
      color: #fff;
      font-weight: 600;
      padding: 12px;
      border-radius: 8px;
      width: 100%;
      transition: 0.3s ease;
    }

    .btn-custom:hover {
      background: linear-gradient(45deg, #a8130eff, #d9fd5870);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .form-check-label {
      font-size: 14px;
      color: #555;
    }

    .text-danger {
      font-weight: 600;
    }
  </style>
</head>

<body>

  <div class="login-form">
    <form action="" method="POST" autocomplete="off">
      <h2>E.E.Ltd</h2>
      <p class="hint-text">Login to continue</p>

      <?php if (!empty($errormsg)) : ?>
        <div class="alert alert-danger text-center py-2"><?php echo $errormsg; ?></div>
      <?php endif; ?>

      <div class="mb-3">
        <input type="text" name="email" class="form-control" placeholder="Email" required autocomplete="off">
      </div>
      <div class="mb-3">
        <input type="password" name="password" class="form-control" placeholder="Password" required autocomplete="new-password">
      </div>

    
      <div class="mb-3">
        <select name="role" class="form-control" required>
          <option value="" disabled selected>Select Role</option>
          <option value="employee">Employee</option>
          <option value="employer">Employer</option>
          <option value="admin">Admin</option>
        </select>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="remember">
          <label class="form-check-label" for="remember">Remember me</label>
        </div>
      </div>
      <button type="submit" name="login" class="btn btn-custom">Login</button>
    </form>
    <p class="text-center mt-3">Don't have an account? 
      <a href="register.php" class="text-danger">Register here</a>
    </p>
  </div>

</body>
</html>
