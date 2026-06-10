<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">

    <h2><i class="fas fa-headset"></i> Agent Panel</h2>

    <a href="/helpdesk/agent/dashboard.php" 
       class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-chart-line"></i> Dashboard
    </a>

    <a href="/helpdesk/agent/my_tickets.php" 
       class="<?= ($currentPage == 'my_tickets.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-ticket"></i> My Tickets
    </a>

    <a href="/helpdesk/agent/profile.php" 
       class="<?= ($currentPage == 'profile.php') ? 'active' : '' ?>">
        <i class="fa-solid fa-user"></i> My Profile
    </a>

    <a href="/helpdesk/logout.php">
        <i class="fa-solid fa-right-from-bracket"></i> Logout
    </a>

</div>

<div class="main-content">

