<?php
include "../config/auth.php";
allowRole(['enduser']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include "../config/db.php";

$uid = $_SESSION['user_id'];

/* ===== STATUS MAP ===== */
$statusMap = [];
$sq = mysqli_query($conn, "SELECT id, name FROM statuses");
while ($row = mysqli_fetch_assoc($sq)) {
    $statusMap[$row['name']] = $row['id'];
}

/* ===== STATS ===== */
$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE created_by = $uid"));
$new = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE created_by = $uid AND status_id = " . intval($statusMap['New'])));
$open = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE created_by = $uid AND status_id = " . intval($statusMap['Open'])));
$closed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE created_by = $uid AND status_id = " . intval($statusMap['Closed'])));

/* ===== RECENT TICKETS ===== */
$res = mysqli_query($conn, "
    SELECT 
        t.id,
        t.title,
        t.priority,
        t.created_at,
        s.name AS status,
        u.name AS agent
    FROM tickets t
    LEFT JOIN statuses s ON t.status_id = s.id
    LEFT JOIN users u ON t.assigned_to = u.id
    WHERE t.created_by = $uid
    ORDER BY t.created_at DESC
    LIMIT 6
");
?>

<div class="container">

<h2>Dashboard</h2>

<!-- SAME AS ADMIN -->
<div class="dashboard-grid">

    <!-- LEFT: STATS -->
    <div class="card">
        <div class="stats">

            <div class="stat-box">
                <h3><?= $total['t'] ?></h3>
                <p>Total Tickets</p>
            </div>

            <div class="stat-box">
                <h3><?= $new['t'] ?></h3>
                <p>New</p>
            </div>

            <div class="stat-box">
                <h3><?= $open['t'] ?></h3>
                <p>Open</p>
            </div>

            <div class="stat-box">
                <h3><?= $closed['t'] ?></h3>
                <p>Closed</p>
            </div>

        </div>
    </div>

    <!-- RIGHT: SIMPLE CTA -->
    <div class="card">
        <h3 style="margin-bottom:10px;">Need Help?</h3>
        <p style="margin-bottom:15px; color:#64748b;">
            Raise a support ticket and get help from our team.
        </p>

        <a href="create_ticket.php" class="btn">
            <i class="fa-solid fa-plus"></i> Create Ticket
        </a>
    </div>

</div>

<!-- FULL WIDTH TABLE (ADMIN STYLE) -->
<div class="card">

    <div class="table-header">
        <h2>My Recent Tickets</h2>
        <a href="my_tickets.php" class="btn btn-sm">View All</a>
    </div>

    <div class="table-container">
        <table>

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>

<?php if(mysqli_num_rows($res) > 0): ?>

<?php while ($t = mysqli_fetch_assoc($res)) { ?>


<tr>

    <td>#TKT-<?= $t['id']; ?></td>

    <td><?= htmlspecialchars($t['title']); ?></td>

    <!-- PRIORITY -->
    <td>
        <span class="badge 
        <?= ($t['priority']=='High') ? 'badge-danger' :
           (($t['priority']=='Medium') ? 'badge-warning' : 'badge-success'); ?>">
            <?= $t['priority']; ?>
        </span>
    </td>

    <!-- STATUS -->
    <td>
        <span class="badge 
        <?= ($t['status']=='New') ? 'badge-info' :
           (($t['status']=='Open') ? 'badge-warning' : 'badge-success'); ?>">
            <?= $t['status']; ?>
        </span>
    </td>

    <!-- AGENT -->
    <td>
        <?php if($t['agent']) { ?>
            <?= $t['agent']; ?>
        <?php } else { ?>
            <span class="badge badge-warning">Unassigned</span>
        <?php } ?>
    </td>

    <!-- CREATED -->
    <td><?= date("d M Y, h:i A", strtotime($t['created_at'])); ?></td>


</tr>

<?php } ?>

<?php else: ?>

<tr>
    <td colspan="6" class="no-data">No tickets found</td>
</tr>

<?php endif; ?>

</tbody>

        </table>
    </div>

</div>

</div>

<?php include __DIR__ . "/layout/footer.php"; ?>