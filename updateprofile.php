<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        // Handle the case if the user is not logged in
        // You can redirect or display an error message
        exit("User not logged in.");
    }

    $user_id = $_SESSION["user_id"];
    $username = $_POST["username"];
    $email = $_POST["email"];
    $bio = $_POST["bio"];

    // Update user details in the database
    $sql = "UPDATE users SET username = ?, email = ?, bio = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $username, $email, $bio, $user_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the profile.php page
    header("Location: profile.php");
    exit;
} else {
    // Handle the case if the request method is not POST
    exit("Invalid request.");
}
?>
