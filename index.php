<!DOCTYPE html>
<html>

<head>
  <title>My Blogging System</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    input[type="text"],
    textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 3px;
    }

    .button-container {
      display: flex;
      gap: 10px;
    }

    .action-button {
      display: flex;
      padding: 10px 15px;
      background-color: rgba(0, 0, 0, 0);
      /* Transparent black */
      color: #000;
      /* Black text */
      border: 1px solid #000;
      /* Black border */
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .action-button:hover {
      background-color: rgba(0, 0, 0, 0.2);
      /* Semi-transparent black on hover */
      color: black;
      /* White text on hover */
    }

    main {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  margin: 5px auto;
}

.post {
  width: calc(35% - 20px); /* Adjust width as needed */
  box-sizing: border-box;
  margin-bottom: 5px; /* Adjust margin as needed */
  padding: 1px; /* Adjust padding as needed */
  border: 1px solid #ddd; /* Add border for better visibility */
  border-radius: 8px; /* Add border-radius for rounded corners */
  background-color: #fff; /* Add background color */
}

  </style>
</head>

<body>
  <header>
    <h1>My Blogging System</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create.php">Create Post</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="login.php" id="login-link">Log In</a></li>
      </ul>
    </nav>
  </header>

  <main>
  <?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'connect.php';
include 'comments.php'; // Make sure you have this file with necessary functions

// Check if the Love button is clicked
if (isset($_GET['love'])) {
    $postID = $_GET['love'];

    // Increment the love_count for the post
    $sql = "UPDATE posts SET love_count = love_count + 1 WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        // Redirect back to the page to update the love count
        header("Location: index.php");
        exit;
    }
}

// Check if the post ID is provided for deletion
if (isset($_GET['delete'])) {
    $postID = $_GET['delete'];

    // Delete the post from the database
    $sql = "DELETE FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();

    // Check if the deletion was successful
    if ($stmt->affected_rows > 0) {
        echo "Post deleted successfully.";
    } else {
        echo "Error deleting post.";
    }

    // Close the statement
    $stmt->close();
}

// Check if the post ID is provided for editing
if (isset($_GET['edit'])) {
    $postID = $_GET['edit'];

    // Fetch the post from the database
    $sql = "SELECT * FROM posts WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the post exists
    if ($result->num_rows > 0) {
        // Display the edit form
        $row = $result->fetch_assoc();
        $content = $row['content'];

        echo "<h2>Edit Post</h2>";
        echo "<section class='post'>";
        // Display the edit form with pre-filled values
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='post_id' value='$postID'>";
        echo "<label>Content:</label><br>";
        echo "<textarea name='content'>$content</textarea><br>";
        echo "<div class='button-container'>";
        echo "<input type='submit' name='edit_post' value='Save' class='action-button'>";
        echo "</div>";
        echo "</form>";
    } else {
        echo "Post not found.";
    }
    echo "</section>";

    // Close the statement
    $stmt->close();
}

// Check if the form is submitted for editing a post
if (isset($_POST['edit_post'])) {
    $postID = $_POST['post_id'];
    $content = $_POST['content'];

    // Update the post in the database
    $sql = "UPDATE posts SET  content = ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $content, $postID);

    // Execute the statement
    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            echo "Post updated successfully.";
        } else {
            echo "No changes were made to the post.";
        }
    } else {
        // Display an error message
        echo "Error updating post: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch posts from the database with the associated username
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.post_date DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Loop through each post
    while ($row = $result->fetch_assoc()) {
        $postID = $row['post_id'];
        $username = $row['username'];
        $content = $row['content'];
        $postDate = $row['post_date'];
        $loveCount = $row['love_count'];

        // Display post content
        echo "<section class='post'>";
        echo "<div class='post-header'>";
        echo "<h2 class='username'>$username</h2>"; // Display username
        // Display delete and edit buttons
        // echo "<div class='button-container'>";
        // echo "<button onclick=\"confirmDelete($postID)\" class='action-button'>Delete</button>";
        // echo "<button onclick=\"editPost($postID)\" class='action-button'>Edit</button>";
        // echo "</div>";

        echo "</div>"; // Close the post-header div

        echo "<p class='meta'>Posted on $postDate</p>";
        echo "<div class='post-content'>";
        echo "<p>$content</p>";

        // Display the image if it exists
        if (!empty($row['image_path'])) {
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Post Image' style='max-width: 100%; display:block; margin:0 auto;'>";
        }

        // Display the Love button and love count to the right of content
        echo "<div class='love-section' style='display: flex; align-items: center;'>";
        echo "<a href='?love=$postID' class='love-button'><i class='fa-regular fa-heart'></i></a>";
        echo "<span class='love-count'>$loveCount</span>";
        echo "</div>";

        echo "</div>"; // Close the post-content div

        // Display the comments container initially hidden
        echo "<div id='comments-container-$postID' class='comments-container' style='display: none;'>";

        // Display the comment toggle button
echo "<button class='action-button' id='toggle-comments-$postID' onclick=\"toggleComments($postID)\">Toggle Comments</button>";

        // Display comments
        $comments = getComments($postID);
        if (!empty($comments)) {
            echo "<div class='comments-section'>";
            echo "<h3>Comments:</h3>";
            echo "<ul>";
            foreach ($comments as $comment) {
                echo "<li>{$comment['user_name']}: {$comment['comment_text']}</li>";
            }
            echo "</ul>";
            echo "</div>";
        }

        // Display the comment form
        echo "<div class='comment-form'>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='post_id' value='$postID'>";
        echo "<label>Your Comment:</label><br>";
        echo "<textarea name='comment_text'></textarea><br>";
        echo "<input type='submit' name='submit_comment' value='Submit Comment'>";
        echo "</form>";
        echo "</div>";

        echo "</div>"; // Close the comments container

        echo "</section>"; // Close the section
    }
} else {
    echo "No posts found.";
}

// Process the submitted comment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
    $postID = $_POST['post_id'];
    $username = 'JohnDoe'; // Replace with the actual username (you may get it from the session)
    $commentText = $_POST['comment_text'];

    if (addComment($postID, $username, $commentText)) {
        // You can display a success message here if needed
    } else {
        echo "Error adding comment.";
    }
}

// Close the database connection
$conn->close();

?>

  </main>
  <script>
    function toggleComments(postID) {
      const commentsContainer = document.getElementById(`comments-container-${postID}`);
      const toggleButton = document.getElementById(`toggle-comments-${postID}`);

      if (commentsContainer.style.display === 'none' || commentsContainer.style.display === '') {
        commentsContainer.style.display = 'block';
        toggleButton.innerText = 'Hide Comments';
      } else {
        commentsContainer.style.display = 'none';
        toggleButton.innerText = 'Toggle Comments';
      }
    }

    function confirmDelete(postID) {
      if (confirm("Are you sure you want to delete this post?")) {
        window.location.href = `?delete=${postID}`;
      }
    }

    function editPost(postID) {
      window.location.href = `?edit=${postID}`;
    }

    function savePost(postID) {
      window.location.reload();
    }
  </script>
  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>
</body>

</html>
