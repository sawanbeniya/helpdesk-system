<?php
include "../config/auth.php";
allowRole(['admin']);
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include "../config/db.php";

/* ===== VALIDATE USER ID ===== */
if (!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit;
}

$id = intval($_GET['id']);

/* ===== FETCH USER ===== */
$res  = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
$user = mysqli_fetch_assoc($res);

// Prevent editing admin account
if ($user && $user['role'] == 'admin') {
    echo "Admin account cannot be edited.";
    exit;
}

/* ===== UPDATE USER ===== */
if (isset($_POST['update'])) {

    // Sanitize inputs
    $name   = mysqli_real_escape_string($conn, $_POST['name']);
    $email  = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $role   = mysqli_real_escape_string($conn, $_POST['role']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Update query
    mysqli_query($conn, "
        UPDATE users SET
        name = '$name',
        email = '$email',
        mobile = '$mobile',
        role = '$role',
        status = '$status'
        WHERE id = $id
    ");

    header("Location: manage_users.php");
    exit;
}
?>
<div class="container">

<div class="card fade-in">
<h2>Edit User</h2>

<form method="POST" class="form-container">

    <div class="form-grid">

        <!-- Name -->
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= $user['name']; ?>" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= $user['email']; ?>" required>
        </div>

        <!-- Mobile -->
        <div class="form-group">
            <label>Mobile</label>
            <input type="text" name="mobile" value="<?= $user['mobile']; ?>">
        </div>

        <!-- Role -->
        <div class="form-group">
            <label>Role</label>
            <select name="role">
                <option value="agent" <?= $user['role'] == 'agent' ? 'selected' : '' ?>>Agent</option>
                <option value="enduser" <?= $user['role'] == 'enduser' ? 'selected' : '' ?>>End User</option>
            </select>
        </div>

        <!-- Status -->
        <div class="form-group form-full">
            <label>Status</label>
            <select name="status">
                <option value="Active" <?= $user['status']=='Active'?'selected':'' ?>>Active</option>
                <option value="Disabled" <?= $user['status']=='Disabled'?'selected':'' ?>>Disabled</option>
            </select>
        </div>

    </div>

    <!-- Button -->
    <div class="form-actions">
        <button name="update" class="btn">Update User</button>
    </div>

</form>

</div>
</div>

<?php include __DIR__ . "/layout/footer.php"; ?>