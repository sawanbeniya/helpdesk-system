<?php
include __DIR__ . "/../config/auth.php";
allowRole(['agent']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

$agent_id = $_SESSION['user_id'];

/* ===== FETCH DEPARTMENTS ===== */
$search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$priority = isset($_GET['priority']) ? $_GET['priority'] : "";
$status   = isset($_GET['status']) ? mysqli_real_escape_string($conn, $_GET['status']) : "";

/* ===== FILTER LOGIC ===== */
$where = "WHERE t.assigned_to = $agent_id";

/* SEARCH */
if (!empty($search)) {
    $where .= " AND (
        t.id LIKE '%$search%' OR
        t.title LIKE '%$search%'
    )";
}

/* PRIORITY */
if (!empty($priority)) {
    $where .= " AND t.priority = '$priority'";
}

/* STATUS */
if (!empty($status)) {
    $where .= " AND s.name = '$status'";
}

/* ===== FETCH TICKETS ===== */
$res = mysqli_query($conn, "
    SELECT 
        t.id,
        t.title,
        t.priority,
        s.name AS status,
        t.created_at
    FROM tickets t
    JOIN statuses s ON t.status_id = s.id
    $where
    ORDER BY t.created_at DESC
");
?>

<div class="container">

    <h2>My Tickets</h2>

    <!-- FILTER CARD -->
    <div class="card">
       <form method="GET" class="filter-form">
    <div class="form-row">

        <!-- SEARCH -->
        <input type="text" name="search" placeholder="Search by ID or title..."
               value="<?= htmlspecialchars($search ?? '') ?>">

        <!-- STATUS -->
        <select name="status">
            <option value="">All Status</option>
            <option value="New" <?= ($status=='New')?'selected':'' ?>>New</option>
            <option value="Open" <?= ($status=='Open')?'selected':'' ?>>Open</option>
            <option value="Closed" <?= ($status=='Closed')?'selected':'' ?>>Closed</option>
        </select>

        <!-- PRIORITY -->
        <select name="priority">
            <option value="">All Priority</option>
            <option value="High" <?= ($priority=='High')?'selected':'' ?>>High</option>
            <option value="Medium" <?= ($priority=='Medium')?'selected':'' ?>>Medium</option>
            <option value="Low" <?= ($priority=='Low')?'selected':'' ?>>Low</option>
        </select>

        <!-- BUTTON -->
        <button type="submit" class="btn btn-filter">
            <i class="fas fa-filter"></i> Filter
        </button>

    </div>
</form>
    </div>

    <!-- TABLE CARD -->
    <div class="card">

        <div class="table-header">
            <h2>Assigned Tickets</h2>
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

                <?php if(mysqli_num_rows($res) > 0): ?>
                    <?php while ($t = mysqli_fetch_assoc($res)) { ?>
                        <tr>

                            <td>#TKT-<?= $t['id']; ?></td>

                            <td><?= htmlspecialchars($t['title']); ?></td>

                            <!-- PRIORITY -->
                            <td>
                                <span class="badge 
                                    <?= ($t['priority'] == 'High') ? 'badge-danger' : 
                                        (($t['priority'] == 'Medium') ? 'badge-warning' : 'badge-success') ?>">
                                    <?= $t['priority'] ?>
                                </span>
                            </td>

                            <!-- STATUS -->
                            <td>
                                <span class="badge 
                                    <?= ($t['status'] == 'Closed') ? 'badge-success' : 
                                        (($t['status'] == 'Open') ? 'badge-warning' : 'badge-info') ?>">
                                    <?= $t['status'] ?>
                                </span>
                            </td>

                            <td><?= date("d M Y, h:i A", strtotime($t['created_at'])); ?></td>

                            <!-- ACTION (FIXED STRUCTURE) -->
                            <td class="actions">

                                <!-- View -->
                                <a href="view_ticket.php?id=<?= $t['id']; ?>" class="btn btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>

                                <!-- Update -->
                                <?php if (strtolower($t['status']) != 'closed') { ?>
                                    <a href="update_ticket.php?id=<?= $t['id']; ?>" class="btn btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                <?php } else { ?>
                                    <button class="btn btn-sm" style="opacity:0.5; cursor:not-allowed;">
                                        <i class="fas fa-check"></i>
                                    </button>
                                <?php } ?>

                            </td>

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