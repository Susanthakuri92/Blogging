<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php';

function getComments($postID) {
    global $conn;
    $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY comment_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addComment($postID, $username, $commentText) {
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
        $username = "TestUser"; // Replace with your authentication logic to get the username

        $success = addComment($postID, $username, $commentText);

        if ($success) {
            header("Location: post_details.php?post_id=$postID");
            exit;
        } else {
            echo "<p>Error adding comment. Please try again.</p>";
        }
    } else {
        echo "<p>Please enter a comment.</p>";
    }
}
?>
