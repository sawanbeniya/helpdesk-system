<?php
include "../config/auth.php";
allowRole(['admin']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

/* Fetch dropdown data */
$departments = mysqli_query($conn, "SELECT * FROM departments");
$categories  = mysqli_query($conn, "SELECT * FROM categories");
$agents      = mysqli_query($conn, "SELECT id, name FROM users WHERE role='agent'");
$statuses    = mysqli_query($conn, "SELECT * FROM statuses");

/* Create ticket */
if (isset($_POST['create_ticket'])) {

    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $department  = intval($_POST['department']);
    $category    = intval($_POST['category']);
    $status_id   = intval($_POST['status_id']);
    $created_by  = $_SESSION['user_id'];

    // Default priority
    $priority = isset($_POST['priority']) && $_POST['priority'] != ""
        ? mysqli_real_escape_string($conn, $_POST['priority'])
        : 'Medium';

    // Handle unassigned ticket
    if (empty($_POST['assigned_to']) || $_POST['assigned_to'] == 0) {
        $assigned_to = "NULL";
    } else {
        $assigned_to = intval($_POST['assigned_to']);
    }

    // Insert ticket
    mysqli_query(
        $conn,
        "
        INSERT INTO tickets
        (
            title,
            description,
            department_id,
            category_id,
            assigned_to,
            status_id,
            priority,
            created_by,
            created_at
        )
        VALUES
        (
            '$title',
            '$description',
            $department,
            $category,
            $assigned_to,
            $status_id,
            '$priority',
            $created_by,
            NOW()
        )
        "
    );

    header("Location: manage_tickets.php");
    exit;
}
?>

<div class="container">

    <h2>Create Ticket</h2>

    <div class="card fade-in">

        <form method="POST" class="form-container">

            <div class="form-grid">

                <!-- Ticket Title -->
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" required>
                </div>

                <!-- Department -->
                <div class="form-group">
                    <label>Department</label>
                    <select name="department" required>
                        <option value="">Select</option>
                        <?php while ($d = mysqli_fetch_assoc($departments)) { ?>
                            <option value="<?= $d['id']; ?>">
                                <?= $d['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Description -->
                <div class="form-group form-full">
                    <label>Description</label>
                    <textarea name="description" required></textarea>
                </div>

                <!-- Category -->
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" required>
                        <option value="">Select</option>
                        <?php while ($c = mysqli_fetch_assoc($categories)) { ?>
                            <option value="<?= $c['id']; ?>">
                                <?= $c['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Priority -->
                <div class="form-group">
                    <label>Priority</label>
                    <select name="priority">
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <!-- Assign Agent -->
                <div class="form-group">
                    <label>Assign Agent</label>
                    <select name="assigned_to">
                        <option value="">Not Assigned</option>
                        <?php while ($a = mysqli_fetch_assoc($agents)) { ?>
                            <option value="<?= $a['id']; ?>">
                                <?= $a['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Ticket Status -->
                <div class="form-group">
                    <label>Status</label>
                    <select name="status_id">
                        <?php while ($s = mysqli_fetch_assoc($statuses)) { ?>
                            <option value="<?= $s['id']; ?>">
                                <?= $s['name']; ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" name="create_ticket" class="btn">
                    Create Ticket
                </button>
            </div>

        </form>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>