<?php
include "../config/auth.php";
allowRole(['admin']);

// Layout
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

include __DIR__ . "/../config/db.php";

/* ======================
   ADD STATUS (NO DUPLICATE)
   ====================== */
if (isset($_POST['add_status'])) {

    $name = trim($_POST['name']);

    if ($name != "") {

        // Sanitize input
        $name = mysqli_real_escape_string($conn, $name);

        // Check duplicate
        $check = mysqli_query($conn, "SELECT id FROM statuses WHERE name = '$name'");

        if (mysqli_num_rows($check) == 0) {
            mysqli_query($conn, "INSERT INTO statuses (name) VALUES ('$name')");
        }
    }
}

/* ======================
   DELETE STATUS (SAFE)
   ====================== */
if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    // Check if status is used in tickets
    $used = mysqli_query($conn, "SELECT id FROM tickets WHERE status_id = $id LIMIT 1");

    if (mysqli_num_rows($used) == 0) {
        mysqli_query($conn, "DELETE FROM statuses WHERE id = $id");
    }
}

/* ======================
   FETCH STATUSES
   ====================== */
$res = mysqli_query($conn, "SELECT * FROM statuses ORDER BY id ASC");
?>
<div class="container">

<h2>Manage Ticket Status</h2>

<!-- ADD STATUS -->
<div class="card fade-in">

    <form method="POST" class="form-row">

        <input type="text" name="name" placeholder="New / Open / Closed" required>

        <button type="submit" name="add_status" class="btn btn-filter">
            <i class="fa fa-plus"></i> Add
        </button>

    </form>

</div>


<!-- STATUS LIST -->
<div class="card fade-in">

    <div class="table-container">
    <table>

        <thead>
        <tr>
            <th>ID</th>
            <th>Status Name</th>
            <th class="action-col">Action</th>
        </tr>
        </thead>

        <tbody>

        <?php while ($s = mysqli_fetch_assoc($res)) { ?>
        <tr>
            <td><?= $s['id']; ?></td>
            <td><?= $s['name']; ?></td>

            <td>
                <div class="actions">

                    <a href="edit_status.php?id=<?= $s['id']; ?>" 
                       class="btn btn-sm">
                       <i class="fas fa-edit"></i> Edit
                    </a>

                    <a href="?delete=<?= $s['id']; ?>" 
                       onclick="return confirm('Delete this status?')"
                       class="btn btn-danger btn-sm">
                       <i class="fa-solid fa-trash"></i> Delete
                    </a>

                </div>
            </td>

        </tr>
        <?php } ?>

        </tbody>

    </table>
    </div>

</div>

</div>


<?php include __DIR__ . "/layout/footer.php"; ?>