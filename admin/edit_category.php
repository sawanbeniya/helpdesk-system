<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

/* ===== VALIDATE ID ===== */
if (!isset($_GET['id'])) {
    header("Location: manage_categories.php");
    exit;
}

$id = intval($_GET['id']);

/* ===== FETCH CATEGORY ===== */
$res = mysqli_query($conn, "SELECT * FROM categories WHERE id = $id");
$cat = mysqli_fetch_assoc($res);

if (!$cat) {
    header("Location: manage_categories.php");
    exit;
}

/* ===== UPDATE ===== */
if (isset($_POST['update'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);

    mysqli_query($conn, "
        UPDATE categories 
        SET name = '$name' 
        WHERE id = $id
    ");

    header("Location: manage_categories.php");
    exit;
}
?>

<div class="container">

    <div class="card fade-in">
        <h2>Edit Category</h2>

        <form method="POST" class="form-container">

            <div class="form-group">
                <label>Category Name</label>
                <input type="text" name="name" value="<?= $cat['name']; ?>" required>
            </div>

            <div class="form-actions">
                <button name="update" class="btn">
                    Update Category
                </button>
            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>