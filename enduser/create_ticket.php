<?php
include "../config/auth.php";
allowRole(['enduser']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include "../config/db.php";

$message = "";

/* ===== CREATE TICKET ===== */
if (isset($_POST['create'])) {

    $title    = mysqli_real_escape_string($conn, $_POST['title']);
    $desc     = mysqli_real_escape_string($conn, $_POST['description']);
    $priority = mysqli_real_escape_string($conn, $_POST['priority']);
    $dept     = intval($_POST['department_id']);
    $cat      = intval($_POST['category_id']);
    $uid      = $_SESSION['user_id'];

    mysqli_query($conn, "
        INSERT INTO tickets
        (title, description, priority, department_id, category_id, status_id, created_by, created_at)
        VALUES
        ('$title', '$desc', '$priority', $dept, $cat,
            (SELECT id FROM statuses WHERE name='New' LIMIT 1),
            $uid, NOW())
    ");

    $message = "✅ Ticket Created Successfully";
}
?>

<div class="container">

    <h2>Create Ticket</h2>

    <div class="card">

        <!-- Message -->
        <?php if ($message) { ?>
            <p class="success"><?= $message; ?></p>
        <?php } ?>

        <div class="form-container">

            <form method="POST">

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" required></textarea>
                </div>

                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Department</label>
                    <select name="department_id" required>
                        <option value="">Select</option>
                        <?php
                        $d = mysqli_query($conn, "SELECT id, name FROM departments");
                        while ($r = mysqli_fetch_assoc($d)) {
                            echo "<option value='{$r['id']}'>" . htmlspecialchars($r['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" required>
                        <option value="">Select</option>
                        <?php
                        $c = mysqli_query($conn, "SELECT id, name FROM categories");
                        while ($r = mysqli_fetch_assoc($c)) {
                            echo "<option value='{$r['id']}'>" . htmlspecialchars($r['name']) . "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" name="create" class="btn">
                        Submit Ticket
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>