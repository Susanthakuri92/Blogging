<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php';

function getComments($postID)
{
    global $conn;
    $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY comment_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addComment($postID, $username, $commentText)
{
    global $conn;
    $sql = "INSERT INTO comments (post_id, user_name, comment_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $postID, $username, $commentText);
    return $stmt->execute();
}

if (isset($_POST['submitComment'])) {
    $postID = $_POST['postID'];
    $commentText = trim($_POST['comment']);

    if (!empty($commentText)) {
        // Replace the following line with your authentication logic to get the username
        $username = "TestUser";

        $success = addComment($postID, $username, $commentText);

        if ($success) {
            header("Location: post_details.php?post_id=$postID");
            exit;
        } else {
            // Provide a user-friendly error message
            echo "<p>Failed to add comment. Please try again later.</p>";
        }
    } else {
        // Provide a user-friendly error message
        echo "<p>Please enter a comment.</p>";
    }
}
ob_end_flush();
?>