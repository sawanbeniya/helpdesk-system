<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

/* ===== VALIDATE ID ===== */
if (!isset($_GET['id'])) {
    header("Location: manage_status.php");
    exit;
}

$id = intval($_GET['id']);

/* ===== FETCH STATUS ===== */
$res = mysqli_query($conn, "SELECT * FROM statuses WHERE id = $id");
$status = mysqli_fetch_assoc($res);

if (!$status) {
    header("Location: manage_status.php");
    exit;
}

/* ===== UPDATE ===== */
if (isset($_POST['update'])) {

    $name = trim($_POST['name']);

    if ($name != "") {

        $name = mysqli_real_escape_string($conn, $name);

        // Optional: prevent duplicate
        $check = mysqli_query($conn, "
            SELECT id FROM statuses 
            WHERE name = '$name' AND id != $id
        ");

        if (mysqli_num_rows($check) == 0) {

            mysqli_query($conn, "
                UPDATE statuses 
                SET name = '$name' 
                WHERE id = $id
            ");
        }
    }

    header("Location: manage_status.php");
    exit;
}
?>

<div class="container">

    <div class="card fade-in">
        <h2>Edit Status</h2>

        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Status Name</label>
                <input type="text" name="name" value="<?= $status['name']; ?>" required>
            </div>

            <div class="form-actions">
                <button name="update" class="btn">
                    Update Status
                </button>
            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>