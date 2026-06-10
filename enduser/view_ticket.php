<?php
include "../config/auth.php";
allowRole(['enduser']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: my_tickets.php");
    exit;
}

$ticket_id = intval($_GET['id']);
$user_id   = $_SESSION['user_id'];

$q = mysqli_query($conn, "
    SELECT 
        t.id,
        t.title,
        t.description,
        t.created_at,
        t.priority,
        s.name AS status,
        d.name AS department,
        c.name AS category,
        u.name AS agent_name,
        u.email AS agent_email,
        u.mobile AS agent_mobile
    FROM tickets t
    JOIN statuses s ON t.status_id = s.id
    JOIN departments d ON t.department_id = d.id
    JOIN categories c ON t.category_id = c.id
    LEFT JOIN users u ON t.assigned_to = u.id
    WHERE t.id = $ticket_id AND t.created_by = $user_id
");

if (!$q || mysqli_num_rows($q) == 0) {
    echo "<div class='container'><div class='card'><p class='no-data'>Access Denied</p></div></div>";
    include __DIR__ . "/layout/footer.php";
    exit;
}

$t = mysqli_fetch_assoc($q);
?>

<div class="container">

    <h2>Ticket Details</h2>

    <div class="card">

        <!-- HEADER (EXACT SAME AS AGENT) -->
        <div class="table-header">
            <h2>#TKT-<?= $t['id']; ?></h2>

            <div style="display:flex; gap:10px; align-items:center;">

                <!-- STATUS -->
                <span class="badge 
                    <?= ($t['status'] == 'Closed') ? 'badge-success' : 
                        (($t['status'] == 'Open') ? 'badge-warning' : 'badge-info') ?>">
                    <?= $t['status'] ?>
                </span>

                <!-- PRIORITY -->
                <span class="badge 
                    <?= ($t['priority'] == 'High') ? 'badge-danger' : 
                        (($t['priority'] == 'Medium') ? 'badge-warning' : 'badge-success') ?>">
                    <?= $t['priority'] ?>
                </span>

                <!-- BACK -->
                <a href="my_tickets.php" class="btn btn-sm">
                    <i class="fas fa-arrow-left"></i>
                </a>

            </div>
        </div>

        <div class="form-container">

            <!-- TITLE -->
            <div class="form-group">
                <label>Title</label>
                <div><?= htmlspecialchars($t['title']); ?></div>
            </div>

            <!-- DESCRIPTION -->
            <div class="form-group">
                <label>Description</label>
                <div style="background:#f8fafc; padding:12px; border-radius:10px; border:1px solid #e2e8f0;">
                    <?= nl2br(htmlspecialchars($t['description'])); ?>
                </div>
            </div>

            <!-- DETAILS GRID (EXACT SAME STRUCTURE) -->
            <div class="form-grid">

                <div class="form-group">
                    <label>Department</label>
                    <div><?= htmlspecialchars($t['department']); ?></div>
                </div>

                <div class="form-group">
                    <label>Category</label>
                    <div><?= htmlspecialchars($t['category']); ?></div>
                </div>

                <div class="form-group">
                    <label>Assigned Agent</label>
                    <div><?= $t['agent_name'] ? htmlspecialchars($t['agent_name']) : 'Not Assigned'; ?></div>
                </div>

                <div class="form-group">
                    <label>Agent Email</label>
                    <div><?= $t['agent_email'] ? htmlspecialchars($t['agent_email']) : '—'; ?></div>
                </div>

                <div class="form-group">
                    <label>Mobile</label>
                    <div><?= $t['agent_mobile'] ? htmlspecialchars($t['agent_mobile']) : '—'; ?></div>
                </div>

                <div class="form-group">
                    <label>Created On</label>
                    <div><?= date("d M Y, h:i A", strtotime($t['created_at'])); ?></div>
                </div>

            </div>

        </div>

    </div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>