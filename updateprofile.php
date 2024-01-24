<?php
session_start();
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["user_id"])) {
        exit("User not logged in.");
    }

    $user_id = $_SESSION["user_id"];
    $username = $_POST["username"];
    $email = $_POST["email"];

    $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
    
    try {
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception("Error in preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssi", $username, $email, $user_id);

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        exit("Error updating user profile: " . $e->getMessage());
    }

    header("Location: profile.php");
    exit;
} else {
    exit("Invalid request.");
}