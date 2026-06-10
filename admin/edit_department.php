<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

/* ===== VALIDATE ID ===== */
if (!isset($_GET['id'])) {
    header("Location: manage_departments.php");
    exit;
}

$id = intval($_GET['id']);

/* ===== FETCH DEPARTMENT ===== */
$res = mysqli_query($conn, "SELECT * FROM departments WHERE id = $id");
$dept = mysqli_fetch_assoc($res);

if (!$dept) {
    header("Location: manage_departments.php");
    exit;
}

/* ===== UPDATE ===== */
if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);

    mysqli_query($conn, "
        UPDATE departments 
        SET name = '$name' 
        WHERE id = $id
    ");

    header("Location: manage_departments.php");
    exit;
}
?>

<div class="container">

    <div class="card fade-in">
        <h2>Edit Department</h2>

        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Department Name</label>
                <input type="text" name="name" value="<?= $dept['name']; ?>" required>
            </div>

            <div class="form-actions">
                <button name="update" class="btn">
                    Update Department
                </button>
            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>