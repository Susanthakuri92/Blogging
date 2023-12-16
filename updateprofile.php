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

    // Update user details in the database
    $sql = "UPDATE users SET username = ?, email = ? WHERE user_id = ?";
    
    // Use a try-catch block for better error handling
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
        // Handle exceptions, log errors, or display a user-friendly message
        exit("Error updating user profile: " . $e->getMessage());
    }

    // Redirect to the profile.php page
    header("Location: profile.php");
    exit;
} else {
    // Handle the case if the request method is not POST
    exit("Invalid request.");
}
?>
