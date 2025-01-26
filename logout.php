<?php
session_start();

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page after logout
header("Location: login.php");
exit();
?>
