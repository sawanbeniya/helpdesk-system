<?php
include "config/db.php";

$message = "";
$success = false;

/* ===== REGISTER USER ===== */
if (isset($_POST['register'])) {

    $name     = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email    = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $mobile   = trim(mysqli_real_escape_string($conn, $_POST['mobile']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {
        $message = "All required fields must be filled";
    } 
    elseif ($password !== $confirm) {
        $message = "Passwords do not match";
    } 
    elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters";
    } 
    else {

        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");

        if (mysqli_num_rows($check) > 0) {
            $message = "Email already registered";
        } 
        else {

            $hash = password_hash($password, PASSWORD_DEFAULT);

            mysqli_query($conn, "
                INSERT INTO users (name, email, mobile, password, role, status)
                VALUES ('$name', '$email', '$mobile', '$hash', 'enduser', 'Active')
            ");

            $message = "Registration successful. You can login now.";
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="/helpdesk/assets/css/modern.css">
</head>

<body class="auth-wrapper">

<div class="auth-container">

    <div class="card auth-card">

        <!-- Header -->
        <div class="auth-header">
            <h2>Create Account</h2>
            <p>Register to start submitting tickets</p>
        </div>

        <!-- Message -->
        <?php if ($message): ?>
            <div class="<?= $success ? 'success-msg' : 'error-msg' ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="form-container">

            <div class="form-grid">

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" placeholder="Enter your name" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" name="mobile" placeholder="Optional">
                </div>

                <!-- Empty grid cell for balance -->
                <div></div>

                <!-- Password -->
                <div class="form-group">
                    <label>Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="password" id="password">
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label>Confirm Password</label>
                    <div class="password-wrapper">
                        <input type="password" name="confirm_password" id="confirm_password">
                        <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
                    </div>
                </div>

            </div>

            <div class="form-actions">
                <button type="submit" name="register" class="btn">
                    Register
                </button>
            </div>

        </form>

        <!-- Footer -->
        <div class="auth-links">
            Already have an account? <a href="login.php">Login</a>
        </div>

    </div>

</div>

<!-- JS -->
<script>
function togglePassword(fieldId, icon) {
    const input = document.getElementById(fieldId);

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>