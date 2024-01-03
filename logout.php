<?php
session_start();

// Clear the session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: login.php");
exit;
?>