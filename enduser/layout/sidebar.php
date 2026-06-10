<div class="sidebar">

    <h2>User Panel</h2>

    <?php
    $current = basename($_SERVER['PHP_SELF']);
    ?>

    <a href="/helpdesk/enduser/dashboard.php" class="<?= $current == 'dashboard.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>

    <a href="/helpdesk/enduser/create_ticket.php" class="<?= $current == 'create_ticket.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-plus"></i> Create Ticket
    </a>

    <a href="/helpdesk/enduser/my_tickets.php" class="<?= $current == 'my_tickets.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-ticket"></i> My Tickets
    </a>

    <a href="/helpdesk/enduser/profile.php" class="<?= $current == 'profile.php' ? 'active' : '' ?>">
        <i class="fa-solid fa-user"></i> My Profile
    </a>

    <a href="/helpdesk/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>

</div>

<div class="main-content">