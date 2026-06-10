<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

/* ===== DELETE DEPARTMENT ===== */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM departments WHERE id = $id");

    header("Location: manage_departments.php");
    exit();
}

/* ===== ADD DEPARTMENT ===== */
if (isset($_POST['add'])) {

    // Sanitize input
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    mysqli_query($conn, "INSERT INTO departments (name) VALUES ('$name')");
}

/* ===== FETCH DEPARTMENTS ===== */
$res = mysqli_query($conn, "SELECT * FROM departments");
?>
<div class="container">

<h2>Departments</h2>

<!-- ADD DEPARTMENT -->
<div class="card fade-in">

<form method="POST" class="form-row">

    <input type="text" name="name" placeholder="Enter Department Name" required>

    <button name="add" class="btn btn-filter">
        <i class="fa fa-plus"></i> Add Department
    </button>

</form>

</div>

<!-- DEPARTMENT LIST -->
<div class="card fade-in">

<div class="table-container">
<table>

<thead>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th class="action-col">Action</th>
</tr>
</thead>

<tbody>

<?php while ($d = mysqli_fetch_assoc($res)) { ?>

<tr>
    <td><?= $d['id']; ?></td>
    <td><?= $d['name']; ?></td>
    <td>

    <div class="actions">

        <a href="edit_department.php?id=<?= $d['id']; ?>" 
           class="btn btn-sm">
           <i class="fas fa-edit"></i> Edit
        </a>

        <a href="?delete=<?= $d['id']; ?>" 
           class="btn btn-danger btn-sm"
           onclick="return confirm('Delete this department?')">
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
<?php include __DIR__ . "/layout/footer.php"; ?>