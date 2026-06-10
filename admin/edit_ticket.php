<?php
include "../config/auth.php";
allowRole(['admin']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

/* ===== VALIDATE ID ===== */
if (!isset($_GET['id'])) {
    header("Location: manage_tickets.php");
    exit;
}

$ticket_id = intval($_GET['id']);

/* ===== FETCH TICKET ===== */
$ticket_q = mysqli_query($conn, "SELECT * FROM tickets WHERE id = $ticket_id LIMIT 1");

if (!$ticket_q || mysqli_num_rows($ticket_q) === 0) {
    header("Location: manage_tickets.php");
    exit;
}

$ticket = mysqli_fetch_assoc($ticket_q);

/* ===== FETCH DROPDOWNS ===== */
$departments = mysqli_query($conn, "SELECT id, name FROM departments");
$categories  = mysqli_query($conn, "SELECT id, name FROM categories");
$agents      = mysqli_query($conn, "SELECT id, name FROM users WHERE role='agent' AND status='Active'");
$statuses    = mysqli_query($conn, "SELECT id, name FROM statuses");

/* ===== UPDATE TICKET ===== */
if (isset($_POST['update_ticket'])) {

    // Sanitize inputs
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $department  = intval($_POST['department']);
    $category    = intval($_POST['category']);
    $assigned_to = intval($_POST['assigned_to']);
    $status_id   = intval($_POST['status_id']);
    $priority    = mysqli_real_escape_string($conn, $_POST['priority']); // FIXED

    // Update query
    mysqli_query($conn, "
        UPDATE tickets SET
        title = '$title',
        description = '$description',
        department_id = $department,
        category_id = $category,
        assigned_to = $assigned_to,
        status_id = $status_id,
        priority = '$priority'
        WHERE id = $ticket_id
    ");

    header("Location: manage_tickets.php");
    exit;
}
?>
<div class="container">

    <h2>Edit Ticket</h2>

    <div class="card fade-in">

<form method="POST" class="form-container">

    <div class="form-grid">

        <!-- Title -->
        <div class="form-group form-full">
            <label>Title</label>
            <input type="text" name="title" value="<?= $ticket['title']; ?>" required>
        </div>

        <!-- Description -->
        <div class="form-group form-full">
            <label>Description</label>
            <textarea name="description" required><?= $ticket['description']; ?></textarea>
        </div>

        <!-- Department -->
        <div class="form-group">
            <label>Department</label>
            <select name="department" required>
                <?php while ($d = mysqli_fetch_assoc($departments)) { ?>
                    <option value="<?= $d['id']; ?>" <?= $ticket['department_id'] == $d['id'] ? 'selected' : '' ?>>
                        <?= $d['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Category -->
        <div class="form-group">
            <label>Category</label>
            <select name="category" required>
                <?php while ($c = mysqli_fetch_assoc($categories)) { ?>
                    <option value="<?= $c['id']; ?>" <?= $ticket['category_id'] == $c['id'] ? 'selected' : '' ?>>
                        <?= $c['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Priority -->
        <div class="form-group">
            <label>Priority</label>
            <select name="priority">
                <option value="Low" <?= ($ticket['priority'] == 'Low') ? 'selected' : ''; ?>>Low</option>
                <option value="Medium" <?= ($ticket['priority'] == 'Medium') ? 'selected' : ''; ?>>Medium</option>
                <option value="High" <?= ($ticket['priority'] == 'High') ? 'selected' : ''; ?>>High</option>
            </select>
        </div>

        <!-- Status -->
        <div class="form-group">
            <label>Status</label>
            <select name="status_id">
                <?php while ($s = mysqli_fetch_assoc($statuses)) { ?>
                    <option value="<?= $s['id']; ?>" <?= $ticket['status_id'] == $s['id'] ? 'selected' : '' ?>>
                        <?= $s['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <!-- Assign -->
        <div class="form-group form-full">
            <label>Assign Agent</label>
            <select name="assigned_to">
                <option value="0">Not Assigned</option>
                <?php while ($a = mysqli_fetch_assoc($agents)) { ?>
                    <option value="<?= $a['id']; ?>" <?= $ticket['assigned_to'] == $a['id'] ? 'selected' : '' ?>>
                        <?= $a['name']; ?>
                    </option>
                <?php } ?>
            </select>
        </div>

    </div>

    <div class="form-actions">
        <button type="submit" name="update_ticket" class="btn">Update Ticket</button>
    </div>

</form>
</div>
</div>
<?php include __DIR__ . "/layout/footer.php"; ?>