<?php
include "../config/auth.php";
allowRole(['admin']);

include __DIR__ . "/layout/header.php";
include __DIR__ . "/layout/sidebar.php";
include __DIR__ . "/../config/db.php";

/* ===== DELETE TICKET ===== */
if (isset($_POST['delete'])) {

    $id = intval($_POST['delete_id']);

    $check = mysqli_query($conn, "
        SELECT s.name 
        FROM tickets t
        JOIN statuses s ON t.status_id = s.id
        WHERE t.id = $id
        LIMIT 1
    ");

    if ($check && mysqli_num_rows($check) === 1) {

        $row = mysqli_fetch_assoc($check);

        if (strtolower(trim($row['name'])) !== 'closed') {
            mysqli_query($conn, "DELETE FROM tickets WHERE id = $id");
        }
    }

   header("Location: manage_tickets.php?msg=deleted");
    exit();
}

/* ===== FILTER ===== */
$search   = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";
$dept   = isset($_GET['dept']) ? intval($_GET['dept']) : 0;
$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
$priority = isset($_GET['priority']) ? $_GET['priority'] : "";

$where = "WHERE 1=1";

if (!empty($search)) {
    $where .= " AND (
        t.id LIKE '%$search%' OR
        t.title LIKE '%$search%' OR
        u2.name LIKE '%$search%'
    )";
}

if ($dept > 0) {
    $where .= " AND t.department_id = $dept";
}

if ($status > 0) {
    $where .= " AND t.status_id = $status";
}

if (!empty($priority)) {
    $where .= " AND t.priority = '$priority'";
}


/* ===== FETCH ===== */
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
    $where
    ORDER BY 
        FIELD(t.priority, 'High', 'Medium', 'Low'),
        t.created_at DESC
");
?>
<div class="container">

    <h2>All Tickets</h2>

    <div class="card fade-in">

        <!-- FILTER -->
       <form method="GET" class="filter-form">

    <div class="form-row">

        <!-- SEARCH -->
        <input type="text" name="search" placeholder="Search by ID, title, user..."
               value="<?= htmlspecialchars($search ?? '') ?>">

        <!-- DEPARTMENT -->
        <select name="dept">
            <option value="">All Departments</option>
            <?php
            $d = mysqli_query($conn, "SELECT id, name FROM departments");
            while ($row = mysqli_fetch_assoc($d)) {
                $selected = ($dept == $row['id']) ? "selected" : "";
                echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
            }
            ?>
        </select>

        <!-- STATUS -->
        <select name="status">
            <option value="">All Status</option>
            <?php
            $s = mysqli_query($conn, "SELECT id, name FROM statuses");
            while ($row = mysqli_fetch_assoc($s)) {
                $selected = ($status == $row['id']) ? "selected" : "";
                echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
            }
            ?>
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
            <i class="fa-solid fa-filter"></i> Filter
        </button>

    </div>

</form>
</div>

<div class="card fade-in">
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
  <?php
$title = $t['title'];

$shortTitle = (strlen($title) > 14) 
    ? substr($title, 0, 14) . '...' 
    : $title;
?>

<td title="<?= htmlspecialchars($title); ?>">
    <?= htmlspecialchars($shortTitle); ?>
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

<?php if (strtolower($t['status']) != 'closed') { ?>

<form method="POST" class="inline-form">
    <input type="hidden" name="delete_id" value="<?= $t['id']; ?>">
    <button type="submit" name="delete" class="btn btn-danger btn-sm"
        onclick="return confirm('Delete this ticket?')">
        <i class="fa-solid fa-trash"></i> Delete
    </button>
</form>

<?php } else { ?>

<button class="btn btn-danger btn-sm btn-disabled">
    <i class="fa-solid fa-lock"></i> Locked
</button>

<?php } ?>

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