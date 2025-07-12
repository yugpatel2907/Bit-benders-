<?php
// Jugaad fix – skip everything and just go to dashboard
session_start();
$_SESSION['username'] = 'Google User'; // placeholder name
$_SESSION['email'] = 'google@example.com'; // optional placeholder email

// Sidhu (straight) redirect
header("Location: dashboard.php");
exit();
?>
