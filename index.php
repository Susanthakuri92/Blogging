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
  justify-content: flex-start;
  margin: 20px auto;
  margin-top: 20px;
}
main a{
  text-decoration: none;
}
.post {
    width: 500px; /* Adjust the width and margin as needed */
    height: 570px;
    margin: 20px auto;
    box-sizing: border-box;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Add a subtle box shadow */
}

/* For larger screens, show 3 posts in a row */
@media screen and (min-width: 768px) {
  main {
    justify-content: space-evenly;
    margin: 10px auto;
    padding: 0 40px;
  }

  .post {
    width: calc(32% - 20px); /* Adjust the width and margin as needed */
    margin: 20px 0;
  }
}

/* For smaller screens, show 1 post in a row */
@media screen and (max-width: 767px) {
  .post {
    width: calc(100% - 20px);
    margin: 20px 0;
  }
}

  </style>
</head>

<body>
  <header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">My Blogging System</a></h1>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create.php">Create Post</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="login.php" id="login-link">Log In</a></li>
      </ul>
    </nav>
    <span class="separator"><i class="fa-solid fa-grip-lines-vertical"></i></span>

    <div class="logout-button" onclick="location.href='logout.php'">
            <i class="fas fa-arrow-right-from-bracket"></i>
            Logout
        </div>

  </header>

  <main>
  <?php
  session_start(); // Start the session
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
  // Redirect to the login page
  header("Location: login.php");
  exit;
}

// Include the database connection file
include 'connect.php';
include 'comments.php'; // Make sure you have this file with necessary functions


// Handle Love Count Update
if (isset($_GET['love'])) {
  $lovePostID = $_GET['love'];

  // Validate user permissions or any other checks before updating love count
  // Use prepared statement to avoid SQL injection
  $sqlUpdateLove = "UPDATE posts SET love_count = love_count + 1 WHERE post_id = ?";
  $stmtUpdateLove = $conn->prepare($sqlUpdateLove);
  $stmtUpdateLove->bind_param("i", $lovePostID);

  if ($stmtUpdateLove->execute()) {
      // Redirect back to the same page to update love count
      header("Location: {$_SERVER['PHP_SELF']}");
      exit();
  } else {
      echo "Error updating love count: " . $stmtUpdateLove->error;
  }

  $stmtUpdateLove->close();
}
// Fetch posts from the database with the associated username
$sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.post_date DESC";
$result = $conn->query($sql);


if ($result === false) {
    echo "Error: " . $conn->error;
} elseif ($result->num_rows > 0) {
    // Loop through each post
    while ($row = $result->fetch_assoc()) {
        $postID = $row['post_id'];
        $username = $row['username'];
        $content = $row['content'];
        $postDate = $row['post_date'];
        $loveCount = $row['love_count'];

        // Display post content
                echo "<a href='post_details.php?post_id=$postID' class='post-link'>";

        echo "<section class='post'>";
        echo "<div class='post-header'>";
        echo "<h2 class='username'>$username</h2>"; // Display username
        echo "</div>"; // Close the post-header div

        echo "<p class='meta'>Posted on $postDate</p>";
        echo "<div class='post-content'>";
        echo "<p>$content</p>";

        // Display the image if it exists
        if (!empty($row['image_path'])) {
            echo "<div style='width: 100%; height: 400px; overflow: hidden;'>";
            echo "<img src='" . htmlspecialchars($row['image_path']) . "' alt='Post Image' class='fill-container'>";
            echo "</div>";
        }

        // Display the Love button and love count to the right of content
echo "<div class='love-section' style='display: flex; align-items: center;'>";
echo "<a href='javascript:void(0);' onclick='updateLoveCount($postID)' class='love-button'><i class='fa-regular fa-heart'></i></a>";
echo "<span class='love-count' id='love-count-$postID'>$loveCount</span>";
echo "</div>";

        echo "</div>"; // Close the post-content div

        echo "</section>"; // Close the section
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
  <script>
    function updateLoveCount(postID) {
    window.location.href = '?love=' + postID;
}
  </script>
</body>

</html>
