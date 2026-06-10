<?php
include "../config/auth.php";
allowRole(['enduser']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include "../config/db.php";

$uid = $_SESSION['user_id'];

/* ===== FETCH USER TICKETS ===== */
$res = mysqli_query($conn, "
    SELECT 
        t.id,
        t.title,
        t.priority,
        t.created_at,
        s.name AS status_name,
        u.name AS agent
    FROM tickets t
    LEFT JOIN statuses s ON t.status_id = s.id
    LEFT JOIN users u ON t.assigned_to = u.id
    WHERE t.created_by = $uid
    ORDER BY t.created_at DESC
");
?>

<div class="container">

    <h2>My Tickets</h2>

    <div class="card">

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
                        <th class="action-col">Action</th>
                    </tr>
                </thead>

                <tbody>

<?php if(mysqli_num_rows($res) > 0): ?>

<?php while ($t = mysqli_fetch_assoc($res)) { ?>

<tr>

    <!-- ID -->
    <td>#TKT-<?= $t['id']; ?></td>

    <!-- TITLE -->
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
        <?= ($t['status_name']=='New') ? 'badge-info' :
           (($t['status_name']=='Open') ? 'badge-warning' : 'badge-success'); ?>">
            <?= $t['status_name']; ?>
        </span>
    </td>

    <!-- ASSIGNED -->
    <td>
        <?php if($t['agent']) { ?>
            <?= htmlspecialchars($t['agent']); ?>
        <?php } else { ?>
            <span class="badge badge-warning">Unassigned</span>
        <?php } ?>
    </td>

    <!-- CREATED -->
    <td><?= date("d M Y, h:i A", strtotime($t['created_at'])); ?></td>

    <!-- ACTION -->
    <td class="actions">
        <a href="view_ticket.php?id=<?= $t['id']; ?>" class="btn btn-sm">
            <i class="fa-solid fa-eye"></i> View
        </a>
    </td>

</tr>

<?php } ?>

<?php else: ?>

<tr>
    <td colspan="7" class="no-data">
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