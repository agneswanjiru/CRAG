<?php 
include 'db.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Enter Username" required><br><br>
        <input type="password" name="password" placeholder="Enter Password" required><br><br>
        <button type="submit" name="login">Login</button>
    </form>

    <?php
    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                header("Location: index.php");
                exit;
            } else {
                echo "Invalid password!";
            }
        } else {
            echo "User not found!";
        }
    }
    ?>
</body>
</html>
