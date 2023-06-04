<!DOCTYPE html>
<html>
<head>
  <title>My Blogging System</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <header>
    <h1>My Blogging System</h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create.html">Create Post</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="login.html" id="login-link">Log In</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <?php
    // Include the database connection file
    include 'connect.php';

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
        $title = $row['title'];
        $content = $row['content'];
        $authorName = $row['author_name'];

        echo "<h2>Edit Post</h2>";
        // Display the edit form with pre-filled values
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='post_id' value='$postID'>";
        echo "<label>Title:</label><br>";
        echo "<input type='text' name='title' value='$title'><br>";
        echo "<label>Content:</label><br>";
        echo "<textarea name='content'>$content</textarea><br>";
        echo "<label>Author Name:</label><br>";
        echo "<input type='text' name='author_name' value='$authorName'><br>";
        echo "<input type='submit' name='edit_post' value='Save'>";
        echo "</form>";
      } else {
        echo "Post not found.";
      }

      // Close the statement
      $stmt->close();
    }

    // Check if the form is submitted for editing a post
    if (isset($_POST['edit_post'])) {
      $postID = $_POST['post_id'];
      $title = $_POST['title'];
      $content = $_POST['content'];
      $authorName = $_POST['author_name'];

      // Update the post in the database
      $sql = "UPDATE posts SET title = ?, content = ?, author_name = ? WHERE post_id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssi", $title, $content, $authorName, $postID);
      $stmt->execute();

      // Check if the update was successful
      if ($stmt->affected_rows > 0) {
        echo "Post updated successfully.";
      } else {
        echo "Error updating post.";
      }

      // Close the statement
      $stmt->close();
    }

    // Fetch posts from the database
    $sql = "SELECT * FROM posts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // Loop through each post
      while ($row = $result->fetch_assoc()) {
        $postID = $row['post_id'];
        $title = $row['title'];
        $content = $row['content'];
        $authorName = $row['author_name'];
        $postDate = $row['post_date'];

        // Display post content
        echo "<section class='post'>";
        echo "<h2>$title</h2>";
        echo "<p class='meta'>Posted on $postDate by $authorName</p>";
        echo "<p>$content</p>";

        // Display delete and edit links
        echo "<p>";
        echo "<a href='?delete=$postID'>Delete</a> | ";
        echo "<a href='?edit=$postID'>Edit</a>";
        echo "</p>";

        echo "</section>";
      }
    } else {
      echo "No posts found.";
    }

    // Close the database connection
    $conn->close();
    ?>
  </main>


  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>
</body>
</html>
