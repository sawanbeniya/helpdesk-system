<?php
include "../config/auth.php";
allowRole(['agent']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

$aid = $_SESSION['user_id'];

/* ===== STATS ===== */
$total = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS t FROM tickets WHERE assigned_to = $aid
"));

$new = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS t 
    FROM tickets 
    WHERE assigned_to = $aid 
    AND status_id = (SELECT id FROM statuses WHERE name = 'New' LIMIT 1)
"));

$open = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS t 
    FROM tickets 
    WHERE assigned_to = $aid 
    AND status_id = (SELECT id FROM statuses WHERE name = 'Open' LIMIT 1)
"));

$closed = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COUNT(*) AS t 
    FROM tickets 
    WHERE assigned_to = $aid 
    AND status_id = (SELECT id FROM statuses WHERE name = 'Closed' LIMIT 1)
"));

/* ===== RECENT TICKETS ===== */
$tickets = mysqli_query($conn, "
    SELECT t.id, t.title, t.created_at, 
           t.priority,
           s.name AS status
    FROM tickets t
    JOIN statuses s ON t.status_id = s.id
    WHERE t.assigned_to = $aid
    ORDER BY t.id DESC
    LIMIT 5
");
?>

<div class="container">

    <h2>Agent Dashboard</h2>

    <!-- Stats -->
    <div class="card">
        <div class="stats">

            <div class="stat-box">
                <h3><?= $total['t'] ?></h3>
                <p>My Tickets</p>
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

    <!-- Recent Tickets -->
    <div class="card">

        <div class="table-header">
            <h2>Recent Tickets</h2>
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
                        <th>Created At</th>
                        <th class="action-col">Action</th>
                    </tr>
                </thead>
                <tbody>

                <?php if(mysqli_num_rows($tickets) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($tickets)): ?>
                        <tr>
                            <td>#TKT-<?= $row['id'] ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>

                            <td>
                                <span class="badge 
                                    <?= ($row['priority'] == 'High') ? 'badge-danger' : 
                                        (($row['priority'] == 'Medium') ? 'badge-warning' : 'badge-success') ?>">
                                    <?= $row['priority'] ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge 
                                    <?= ($row['status'] == 'Closed') ? 'badge-success' : 
                                        (($row['status'] == 'Open') ? 'badge-warning' : 'badge-info') ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>

                            
                            <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>


                            <td class="actions">
                                <a href="view_ticket.php?id=<?= $row['id'] ?>" class="btn btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
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

