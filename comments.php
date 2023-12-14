<!-- comments.php -->
<?php
// Include necessary files and configurations
include 'connect.php';

// Retrieve comments for a specific post using postID
function getComments($postID) {
    global $conn;
    $sql = "SELECT * FROM comments WHERE post_id = ? ORDER BY comment_date ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Add a comment to the database
function addComment($postID, $username, $commentText) {
    global $conn;
    $sql = "INSERT INTO comments (post_id, user_name, comment_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $postID, $username, $commentText);
    return $stmt->execute();
}
?>
