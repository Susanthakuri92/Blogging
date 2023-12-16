<?php
ob_start();
$host = "localhost";
$username = "root";
$password = "0092";
$database = "canteenmate";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the cookie
$cookie_name = "example_cookie";
$cookie_value = "example_value";
$cookie_expiry = time() + (86400 * 30); // Cookie will expire in 30 days (86400 seconds per day)

setcookie($cookie_name, $cookie_value, $cookie_expiry, "/"); // "/" means the cookie is available for the entire domain
ob_end_flush();
?>