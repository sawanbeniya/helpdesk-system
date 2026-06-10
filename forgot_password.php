<?php
include "config/db.php";

$message = "";
$success = false;

/* ===== RESET PASSWORD ===== */
if (isset($_POST['reset'])) {

    $email        = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $new_password = trim($_POST['new_password']);
    $confirm      = trim($_POST['confirm_password']);

    if (empty($email) || empty($new_password) || empty($confirm)) {
        $message = "All fields are required";
    } 
    elseif ($new_password !== $confirm) {
        $message = "Passwords do not match";
    } 
    elseif (strlen($new_password) < 6) {
        $message = "Password must be at least 6 characters";
    } 
    else {

        $check = mysqli_query($conn, "SELECT id, status FROM users WHERE email = '$email' LIMIT 1");

        if (mysqli_num_rows($check) == 0) {
            $message = "Email not found";
        } 
        else {

            $user = mysqli_fetch_assoc($check);

            if ($user['status'] !== 'Active') {
                $message = "Account is disabled";
            } 
            else {

                $user_id = $user['id'];
                $hash    = password_hash($new_password, PASSWORD_DEFAULT);

                mysqli_query($conn, "
                    UPDATE users SET password = '$hash' WHERE id = $user_id
                ");

                $message = "Password reset successful. Redirecting to login...";
                $success = true;

                header("refresh:2;url=login.php");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>

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
            <h2>Reset Password</h2>
            <p>Enter your email and set a new password</p>
        </div>

        <!-- Message -->
        <?php if ($message): ?>
            <div class="<?= $success ? 'success-msg' : 'error-msg' ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- New Password -->
            <div class="form-group">
                <label>New Password</label>
                <div class="password-wrapper">
                    <input type="password" name="new_password" id="new_password" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('new_password', this)"></i>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="password-wrapper">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="reset" class="btn">
                    Reset Password
                </button>
            </div>

        </form>

        <!-- Footer -->
        <div class="auth-links">
            <a href="login.php">Back to Login</a>
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