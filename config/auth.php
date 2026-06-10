<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* LOGIN CHECK */
if (!isset($_SESSION['user_id'])) {
    header("Location: /helpdesk/login.php");
    exit;
}

/* ROLE CHECK */
if (!function_exists('allowRole')) {
    function allowRole($roles = []) {

        if (!in_array($_SESSION['role'], $roles)) {

            if ($_SESSION['role'] == 'admin') {
                header("Location: /helpdesk/admin/dashboard.php");
            } elseif ($_SESSION['role'] == 'agent') {
                header("Location: /helpdesk/agent/dashboard.php");
            } else {
                header("Location: /helpdesk/enduser/dashboard.php");
            }
            exit;
        }
    }
}