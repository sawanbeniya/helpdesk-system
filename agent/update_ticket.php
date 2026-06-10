<?php
include __DIR__ . "/../config/auth.php";
allowRole(['agent']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

/* ===== VALIDATE ID ===== */
if (!isset($_GET['id'])) {
    header("Location: my_tickets.php");
    exit;
}

$ticket_id = intval($_GET['id']);
$agent_id  = $_SESSION['user_id'];

/* ===== FETCH TICKET ===== */
$q = mysqli_query($conn, "
    SELECT 
        t.id,
        s.id AS status_id,
        s.name AS status_name
    FROM tickets t
    JOIN statuses s ON t.status_id = s.id
    WHERE t.id = $ticket_id AND t.assigned_to = $agent_id
");

if (!$q || mysqli_num_rows($q) == 0) {
    echo "<div class='container'><div class='card'><p class='no-data'>Access Denied</p></div></div>";
    include __DIR__ . "/layout/footer.php";
    exit;
}

$ticket = mysqli_fetch_assoc($q);

/* ===== BLOCK CLOSED ===== */
if (strtolower($ticket['status_name']) == 'closed') {
    echo "<div class='container'><div class='card'><p class='no-data'>This ticket is already closed.</p></div></div>";
    include __DIR__ . "/layout/footer.php";
    exit;
}

/* ===== STATUS IDS ===== */
$status_new = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM statuses WHERE name = 'New'"))['id'];
$status_open = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM statuses WHERE name = 'Open'"))['id'];
$status_closed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM statuses WHERE name = 'Closed'"))['id'];

/* ===== UPDATE ===== */
if (isset($_POST['update_status'])) {

    $new_status = intval($_POST['status_id']);

    if (
        ($ticket['status_id'] == $status_new && $new_status == $status_open) ||
        ($ticket['status_id'] == $status_open && $new_status == $status_closed)
    ) {
        mysqli_query($conn, "UPDATE tickets SET status_id = $new_status WHERE id = $ticket_id");
    }

    header("Location: my_tickets.php");
    exit;
}
?>

<div class="container">

    <h2>Update Ticket Status</h2>

    <div class="card">

        <div class="form-container">

            <!-- CURRENT STATUS -->
            <div class="form-group">
                <label>Current Status</label>
                <div>
                    <span class="badge 
                        <?= ($ticket['status_name'] == 'Closed') ? 'badge-success' : 
                            (($ticket['status_name'] == 'Open') ? 'badge-warning' : 'badge-info') ?>">
                        <?= htmlspecialchars($ticket['status_name']); ?>
                    </span>
                </div>
            </div>

            <!-- FORM -->
            <form method="POST">

                <div class="form-group">
                    <label>Change Status</label>

                    <select name="status_id" required>

                        <?php if ($ticket['status_name'] == 'New') { ?>
                            <option value="<?= $status_open; ?>">Open</option>
                        <?php } elseif ($ticket['status_name'] == 'Open') { ?>
                            <option value="<?= $status_closed; ?>">Closed</option>
                        <?php } ?>

                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" name="update_status" class="btn">
                        <i class="fas fa-save"></i> Update Status
                    </button>

                    <a href="my_tickets.php" class="btn btn-sm" style="margin-left:10px;">
                        Back
                    </a>
                </div>

            </form>

        </div>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>
