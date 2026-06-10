<?php
include "../config/auth.php";
allowRole(['admin']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

/* ======================
   TOGGLE USER STATUS
   ====================== */
if (isset($_GET['toggle'])) {

    $id = intval($_GET['toggle']);
    $current_user = $_SESSION['user_id'];

    // Prevent self-toggle
    if ($id != $current_user) {

        $res = mysqli_query($conn, "SELECT role, status FROM users WHERE id = $id LIMIT 1");

        if ($res && mysqli_num_rows($res) === 1) {

            $user = mysqli_fetch_assoc($res);

            // Prevent admin status change
            if ($user['role'] != 'admin') {

                $new_status = ($user['status'] == 'Active') ? 'Disabled' : 'Active';

                mysqli_query($conn, "UPDATE users SET status = '$new_status' WHERE id = $id");
            }
        }
    }

    header("Location: manage_users.php");
    exit();
}

/* ======================
   SEARCH + FILTER
   ====================== */
$search = isset($_GET['search']) 
    ? trim(mysqli_real_escape_string($conn, $_GET['search'])) 
    : '';

$role = isset($_GET['role']) 
    ? mysqli_real_escape_string($conn, $_GET['role']) 
    : '';

$status = isset($_GET['status']) 
    ? mysqli_real_escape_string($conn, $_GET['status']) 
    : '';

$where = "WHERE 1=1";

if ($search !== '') {
    $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}

if ($role !== '') {
    $where .= " AND role = '$role'";
}

if ($status !== '') {
    $where .= " AND status = '$status'";
}

/* ======================
   FETCH USERS
   ====================== */
$res = mysqli_query($conn, "
    SELECT id, name, email, role, status
    FROM users
    $where
    ORDER BY id DESC
");
?>
<div class="container">
    
    <h2>Manage Users</h2>
    
   <div class="card fade-in">
        
<form method="GET" class="filter-form">

    <div class="form-row">

        <input type="text" name="search" placeholder="Search by name/email" value="<?= $search; ?>">

        <select name="role">
            <option value="">All Roles</option>
            <option value="admin" <?= ($role == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="agent" <?= ($role == 'agent') ? 'selected' : ''; ?>>Agent</option>
            <option value="enduser" <?= ($role == 'enduser') ? 'selected' : ''; ?>>End User</option>
        </select>

        <select name="status">
            <option value="">All Status</option>
            <option value="Active" <?= ($status == 'Active') ? 'selected' : ''; ?>>Active</option>
            <option value="Disabled" <?= ($status == 'Disabled') ? 'selected' : ''; ?>>Disabled</option>
        </select>

       <button type="submit" class="btn btn-filter">
    <i class="fa-solid fa-filter"></i> Filter
</button>
        <a href="add_user.php" class="btn btn-filter">
    <i class="fa-solid fa-plus"></i> Add User
</a>

    </div>

</form>

</div>

    <div class="card fade-in">
    <!-- TABLE -->
    <div class="table-container">
    <table>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php if(mysqli_num_rows($res) > 0): ?>

    <?php while ($u = mysqli_fetch_assoc($res)) { ?>
        <tr>
            <td><?= htmlspecialchars($u['name']); ?></td>
            <td><?= htmlspecialchars($u['email']); ?></td>
            <td>
    <span class="badge 
        <?= ($u['role']=='admin') ? 'badge-danger' : 
           (($u['role']=='agent') ? 'badge-info' : 'badge-success'); ?>">
        <?= ucfirst($u['role']); ?>
    </span>
</td>
            <td>
    <span class="badge <?= ($u['status'] == 'Active') ? 'badge-success' : 'badge-danger'; ?>">
        <?= $u['status']; ?>
    </span>
</td>
            <td class="actions">

<?php if ($u['role'] == 'admin') { ?>
    ---
<?php } elseif ($u['id'] == $_SESSION['user_id']) { ?>
    <span class="badge badge-info">You</span>
<?php } else { ?>

    <a href="edit_user.php?id=<?= $u['id']; ?>" class="btn btn-sm">
        <i class="fas fa-edit"></i> Edit
    </a>

    <a href="?toggle=<?= $u['id']; ?>"
       onclick="return confirm('Are you sure?')"
       class="btn btn-sm <?= ($u['status'] == 'Active') ? 'btn-danger' : ''; ?>">

       <i class="fa-solid fa-ban"></i>
       <?= ($u['status'] == 'Active') ? 'Disable' : 'Enable'; ?>
    </a>

<?php } ?>

</td>
        </tr>
            <?php } ?>

<?php else: ?>

<tr>
    <td colspan="5" class="no-data">
        <i class="fa-solid fa-users"></i><br>
        No users found
    </td>
</tr>

<?php endif; ?>

    </table>
</div>
</div>
</div>

<?php include __DIR__ . "/layout/footer.php"; ?>