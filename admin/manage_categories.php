<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
/* ===== DELETE CATEGORY ===== */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");

    header("Location: manage_categories.php");
    exit();
}

/* ===== ADD CATEGORY ===== */
if (isset($_POST['add'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);

    mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
}

/* ===== FETCH ===== */
$res = mysqli_query($conn, "SELECT * FROM categories");
?>
<div class="container">
    
    <h2>Categories</h2>

    <!-- ADD CATEGORY -->
    <div class="card fade-in">
        <form method="POST" class="form-row">

            <input type="text" name="name" placeholder="Enter Category Name" required>

            <button name="add" class="btn btn-filter">
                <i class="fa fa-plus"></i> Add
            </button>

        </form>
    </div>

    <!-- CATEGORY LIST -->
    <div class="card fade-in">

        <div class="table-container">
        <table>

            <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th class="action-col">Action</th>
            </tr>
            </thead>

            <tbody>

            <?php while ($c = mysqli_fetch_assoc($res)) { ?>
            <tr>
                <td><?= $c['id']; ?></td>
                <td><?= $c['name']; ?></td>

                <td>
                    <div class="actions">

                        <a href="edit_category.php?id=<?= $c['id']; ?>" 
                           class="btn btn-sm">
                           <i class="fas fa-edit"></i> Edit
                        </a>

                        <a href="?delete=<?= $c['id']; ?>"
                           onclick="return confirm('Delete this category?')"
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