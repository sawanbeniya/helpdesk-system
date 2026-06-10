<?php
include "../config/auth.php";
allowRole(['admin']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

$user_id = $_SESSION['user_id'];
$message = "";

/* ===== FETCH USER ===== */
$res  = mysqli_query($conn, "SELECT name, email, mobile, role, password FROM users WHERE id = $user_id");
$user = mysqli_fetch_assoc($res);

/* ===== UPDATE PROFILE ===== */
if (isset($_POST['update_profile'])) {

    // Sanitize inputs
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

    mysqli_query($conn, "
        UPDATE users 
        SET name = '$name', email = '$email', mobile = '$mobile' 
        WHERE id = $user_id
    ");

    $message = "✅ Profile updated successfully";

    // Refresh user data
    $res  = mysqli_query($conn, "SELECT name, email, mobile, role, password FROM users WHERE id = $user_id");
    $user = mysqli_fetch_assoc($res);
}

/* ===== CHANGE PASSWORD ===== */
if (isset($_POST['change_password'])) {

    $old     = $_POST['old_password'];
    $new     = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    if (!password_verify($old, $user['password'])) {
        $message = "❌ Old password is incorrect";
    } elseif ($new != $confirm) {
        $message = "❌ New passwords do not match";
    } elseif (strlen($new) < 6) {
        $message = "❌ Password must be at least 6 characters";
    } else {

        $hash = password_hash($new, PASSWORD_DEFAULT);

        mysqli_query($conn, "UPDATE users SET password = '$hash' WHERE id = $user_id");

        $message = "✅ Password updated successfully";
    }
}
?>

<div class="container">

<h2>My Profile</h2>

<!-- Message -->
<?php if ($message): ?>
    <div class="card">
        <div class="no-data" style="padding:10px;">
            <?= $message ?>
        </div>
    </div>
<?php endif; ?>


<!-- PROFILE INFO -->
<div class="card fade-in">

    <h3>Profile Information</h3>

    <form method="POST" class="form-container">

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" value="<?= htmlspecialchars($user['mobile']); ?>">
        </div>

        <div class="form-group">
            <label>Role</label>
            <input type="text" value="<?= ucfirst($user['role']); ?>" disabled>
        </div>

        <div class="form-actions">
            <button type="submit" name="update_profile" class="btn">
                <i class="fas fa-save"></i> Update Profile
            </button>
        </div>

    </form>

</div>


<!-- SECURITY -->
<div class="card fade-in">

    <h3>Security Settings</h3>

    <form method="POST" class="form-container">

        <div class="form-group">
            <label>Old Password</label>
            <input type="password" name="old_password" required>
        </div>

        <div class="form-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="form-actions">
            <button type="submit" name="change_password" class="btn">
                <i class="fas fa-lock"></i> Update Password
            </button>
        </div>

    </form>

</div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>