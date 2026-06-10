<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

$msg = "";

/* Handle Add User Form */
if (isset($_POST['add_user'])) {

    // Sanitize inputs
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile   = mysqli_real_escape_string($conn, $_POST['mobile']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];

    // Validate required fields
    if (empty($name) || empty($email) || empty($password)) {

        $msg = "❌ Please fill all required fields";

    } elseif (strlen($password) < 6) {

        $msg = "❌ Password must be at least 6 characters";

    } else {

        // Hash password before saving
        $pass = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        mysqli_query(
            $conn,
            "
            INSERT INTO users (name, email, mobile, role, password, status)
            VALUES ('$name', '$email', '$mobile', '$role', '$pass', 'active')
            "
        );

        $msg = "✅ User added successfully";
    }
}
?>

<div class="container">

    <h2>Add New User</h2>

    <div class="card fade-in">

        <!-- Status Message -->
        <?php if ($msg): ?>
            <div class="form-group">
                <span class="badge <?= (strpos($msg, 'success') !== false) ? 'badge-success' : 'badge-danger'; ?>">
                    <?= $msg ?>
                </span>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-container">

            <div class="form-grid">

                <!-- User Name -->
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" required>
                </div>

                <!-- User Email -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <!-- User Mobile -->
                <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" name="mobile">
                </div>

                <!-- User Role -->
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" required>
                        <option value="agent">Agent</option>
                        <option value="enduser">End User</option>
                    </select>
                </div>

                <!-- Initial Password -->
                <div class="form-group form-full">
                    <label>Initial Password</label>
                    <input type="password" name="password" required>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button name="add_user" class="btn">
                    <i class="fa-solid fa-user-plus"></i> Add User
                </button>
            </div>

        </form>

    </div>

    <p style="color:#64748b; margin-top:10px;">
        User can change password after first login.
    </p>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>