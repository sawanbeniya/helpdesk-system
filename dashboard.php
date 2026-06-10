<?php
session_start();

/* ===== CHECK LOGIN ===== */
if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];

/* ===== ROLE-BASED REDIRECT ===== */
if ($role == 'admin') {
    header("Location: admin/dashboard.php");
} elseif ($role == 'agent') {
    header("Location: agent/dashboard.php");
} elseif ($role == 'enduser') {
    header("Location: enduser/dashboard.php");
} else {
    // Unknown role → logout for safety
    session_destroy();
    header("Location: login.php");
}

exit;