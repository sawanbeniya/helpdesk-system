<?php
session_start();

/* ===== DESTROY SESSION ===== */
$_SESSION = [];          // Clear all session data
session_unset();         // Unset variables
session_destroy();       // Destroy session

/* ===== REDIRECT TO LOGIN ===== */
header("Location: login.php");
exit;