<?php
// Safe mail helper (no redeclare error)
if (!function_exists('sendMail')) {

    function sendMail($to, $subject, $message) {

        // BASIC mail headers (simple & exam-safe)
        $headers  = "From: helpdesk@localhost\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Localhost/XAMPP me mail usually disabled hota hai,
        // so we fail silently (project break nahi hoga)
        @mail($to, $subject, $message, $headers);
    }
}
