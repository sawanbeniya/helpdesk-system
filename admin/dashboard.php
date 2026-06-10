<?php
include "../config/auth.php";
allowRole(['admin']);

include "../config/db.php";
include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";

/* ===== GET STATUS IDs DYNAMICALLY ===== */
$statusMap = [];

$statusQuery = mysqli_query($conn, "SELECT id, name FROM statuses");
while ($row = mysqli_fetch_assoc($statusQuery)) {
    $statusMap[$row['name']] = $row['id'];
}

/* ===== FETCH DASHBOARD STATS ===== */

// Total tickets
$total = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets")
);

// New tickets
$new = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE status_id = " . intval($statusMap['New']))
);

// Open tickets
$open = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE status_id = " . intval($statusMap['Open']))
);

// Closed tickets
$closed = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS t FROM tickets WHERE status_id = " . intval($statusMap['Closed']))
);

/* ===== RECENT TICKETS ===== */
$res = mysqli_query($conn, "
    SELECT 
        t.id,
        t.title,
        t.priority,
        t.created_at,
        s.name AS status,
        u1.name AS agent,
        u2.name AS raised_by
    FROM tickets t
    LEFT JOIN statuses s ON t.status_id = s.id
    LEFT JOIN users u1 ON t.assigned_to = u1.id
    LEFT JOIN users u2 ON t.created_by = u2.id
    ORDER BY t.created_at DESC
    LIMIT 6
");
?>

<div class="container">

<h2>Admin Dashboard</h2>

<div class="dashboard-grid">

    <!-- LEFT: STATS -->
    <div class="card fade-in">
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

    <!-- RIGHT: CHART -->
    <div class="card fade-in">
        <h3 style="margin-bottom:10px;">Ticket Overview</h3>
        <canvas id="ticketChart"></canvas>
    </div>

</div>

<!-- BELOW: TABLE -->
<div class="card fade-in">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
    <h3>Recent Tickets</h3>
    <a href="manage_tickets.php" class="btn btn-sm">View All</a>
</div>

 <!-- TABLE -->
        <div class="table-container">
            <table>

                <thead>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>Priority</th>
    <th>Status</th>
    <th>Assigned To</th>
    <th>Created By</th>
    <th>Created At</th>
    <th>Action</th>
</tr>
</thead>

                <tbody>

<?php if(mysqli_num_rows($res) > 0): ?>

<?php while ($t = mysqli_fetch_assoc($res)) { ?>



<tr>

    <!-- ID -->
    <td>#TKT-<?= $t['id']; ?></td>

    <!-- TITLE (CLICKABLE) -->
    <td title="<?= $t['title']; ?>">
        <a href="edit_ticket.php?id=<?= $t['id']; ?>" style="text-decoration:none; color:inherit;">
            <?= $t['title']; ?>
        </a>
    </td>

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

    <!-- RAISED BY -->
    <td><?= $t['raised_by'] ?? 'Unknown'; ?></td>

    <!-- CREATED -->
    <td><?= date("d M Y, h:i A", strtotime($t['created_at'])); ?></td>


    <!-- ACTION -->
    <td class="actions">

        <a href="edit_ticket.php?id=<?= $t['id']; ?>" class="btn btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>


    </td>

</tr>

<?php } ?>

<?php else: ?>

<tr>
    <td colspan="8" class="no-data">
        <i class="fa-solid fa-inbox"></i><br>
        No tickets found
    </td>
</tr>

<?php endif; ?>

</tbody>

            </table>
    </div>
</div>

</div>
<?php include __DIR__ . "/layout/footer.php"; ?>