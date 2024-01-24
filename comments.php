<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php';

function getComments($postID)
{
    global $conn;
    $sql = "SELECT comments.*, users.username AS user_name FROM comments JOIN users ON comments.user_id = users.user_id WHERE post_id = ? ORDER BY comment_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function addComment($postID, $userID, $commentText)
{
    global $conn;
    $sql = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $postID, $userID, $commentText);
    return $stmt->execute();
}

if (isset($_POST['submitComment'])) {
    $postID = $_POST['postID'];
    $commentText = trim($_POST['comment']);

    if (!empty($commentText)) {
        session_start();
        if (isset($_SESSION['user_id'])) {
            $userID = $_SESSION['user_id'];
        } else {
            header("Location: login.php");
            exit;
        }

        $success = addComment($postID, $userID, $commentText);

        if ($success) {
            header("Location: post_details.php?post_id=$postID");
            exit;
        } else {
            echo "<p>Failed to add comment. Please try again later.</p>";
        }
    } else {
        echo "<p>Please enter a comment.</p>";
    }
}
