<?php $page = basename($_SERVER['PHP_SELF']); ?>

<div class="wrapper">

    <div class="sidebar">
        <h2>Admin Panel</h2>

        <a href="dashboard.php" class="<?= ($page=='dashboard.php')?'active':'' ?>">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>

        <a href="create_ticket.php" class="<?= ($page=='create_ticket.php')?'active':'' ?>">
            <i class="fa-solid fa-plus"></i> Create Ticket
        </a>

        <a href="manage_tickets.php" class="<?= ($page=='manage_tickets.php')?'active':'' ?>">
            <i class="fa-solid fa-ticket"></i> Tickets
        </a>

        <a href="manage_users.php" class="<?= ($page=='manage_users.php')?'active':'' ?>">
            <i class="fa-solid fa-users"></i> Users
        </a>

        <a href="manage_departments.php" class="<?= ($page=='manage_departments.php')?'active':'' ?>">
            <i class="fa-solid fa-building"></i> Departments
        </a>

        <a href="manage_categories.php" class="<?= ($page=='manage_categories.php')?'active':'' ?>">
            <i class="fa-solid fa-layer-group"></i> Categories
        </a>

        <a href="manage_status.php" class="<?= ($page=='manage_status.php')?'active':'' ?>">
            <i class="fa-solid fa-list-check"></i> Status
        </a>

        <a href="profile.php" class="<?= ($page=='profile.php')?'active':'' ?>">
            <i class="fa-solid fa-user"></i> My Profile
        </a>

        <a href="../logout.php">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </a>
    </div>

    <!-- MAIN CONTENT START -->
    <div class="main-content">
        <div class="container">