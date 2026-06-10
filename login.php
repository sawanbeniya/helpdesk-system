<?php
session_start();
include "config/db.php";

$error = "";

/* ===== LOGIN PROCESS ===== */
if (isset($_POST['login'])) {

    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' LIMIT 1");

    if ($res && mysqli_num_rows($res) === 1) {

        $u = mysqli_fetch_assoc($res);

        if ($u['status'] !== 'Active') {
            $error = "Account is disabled";
        }
        elseif (!password_verify($password, $u['password'])) {
            $error = "Invalid password";
        }
        else {

            session_regenerate_id(true);

            $_SESSION['user_id'] = $u['id'];
            $_SESSION['role']    = $u['role'];
            $_SESSION['name']    = $u['name'];

            if ($u['role'] === 'admin') {
                header("Location: /helpdesk/admin/dashboard.php");
            } 
            elseif ($u['role'] === 'agent') {
                header("Location: /helpdesk/agent/dashboard.php");
            } 
            elseif ($u['role'] === 'enduser') {
                header("Location: /helpdesk/enduser/dashboard.php");
            } 
            else {
                session_destroy();
                header("Location: login.php");
            }

            exit;
        }

    } else {
        $error = "User not found";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HelpDesk Login</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="/helpdesk/assets/css/modern.css">
</head>

<body class="auth-wrapper">

<div class="auth-container">

    <div class="card auth-card">

        <!-- Header -->
        <div class="auth-header">
            <h2>HelpDesk Login</h2>
            <p>Access your account to manage tickets</p>
        </div>

        <!-- Error -->
        <?php if ($error): ?>
            <div class="error-msg">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
    <label>Password</label>

    <div class="password-wrapper">
        <input type="password" name="password" id="password" placeholder="Enter your password" required>

        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword()"></i>
    </div>
</div>

            <div class="form-actions">
                <button type="submit" name="login" class="btn">
                    Login
                </button>
            </div>

        </form>

        <!-- Links -->
        <div class="auth-links">
            <a href="forgot_password.php">Forgot Password?</a><br>
            <a href="register.php">Create Account</a>
        </div>

    </div>

</div>

<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (pwd.type === "password") {
        pwd.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        pwd.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>
</body>
</html>