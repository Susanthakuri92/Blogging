<!DOCTYPE html>
<html>

<head>
  <title>My Blogging System</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="css/index.css">
</head>

<body>
  <header>
    <h1><a href="index.php" style="text-decoration: none; color: inherit;">My Blogging System</a></h1>
    <form method="get" class="search-form">
      <div class="search-container">
        <input type="text" name="query" id="search" placeholder="Search Post">
        <button type="submit">Search</button>
      </div>
    </form>
    <nav>
      <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="create.php">Create Post</a></li>
        <!-- <li><a href="profile.php">Profile</a></li>
        <li><a href="login.php" id="login-link">Log In</a></li> -->
        <?php
        session_start();
        if (isset($_SESSION["user_id"])) {
          // --------profile image--------------
          include 'connect.php';

          $user_id = $_SESSION["user_id"];
          $sql = "SELECT profile_image FROM users WHERE user_id = ?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i", $user_id);

          if (!$stmt->execute()) {
            echo "SQL Error: " . $stmt->error;
            exit;
          }

          $result = $stmt->get_result();

          if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $profile_image = $row["profile_image"];
          }

          $stmt->close();
          $conn->close();

          // -------profile image else default image--------
          echo '<li><a href="profile.php"><img src="' . (isset($profile_image) ? $profile_image : 'uploads/default_profile.jpg') . '" alt="Profile Image" class="profile-image-nav"></a></li>';
        } else {
          // -------login link if not logged in -------------
          echo '<li><a href="login.php" id="login-link">Log In</a></li>';
        }
        ?>

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
    session_start(); 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // ------check if login or not---------

    if (!isset($_SESSION["user_id"])) {
      header("Location: login.php");
      exit;
    }

    include 'connect.php';
    include 'comments.php'; 
    
    // ------love count update---------
    if (isset($_GET['love'])) {
      $lovePostID = $_GET['love'];

      $sqlUpdateLove = "UPDATE posts SET love_count = love_count + 1 WHERE post_id = ?";
      $stmtUpdateLove = $conn->prepare($sqlUpdateLove);
      $stmtUpdateLove->bind_param("i", $lovePostID);

      if ($stmtUpdateLove->execute()) {
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
      } else {
        echo "Error updating love count: " . $stmtUpdateLove->error;
      }

      $stmtUpdateLove->close();
    }

    if (isset($_GET['query'])) {
      $query = $_GET['query'];

      $sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id 
              WHERE content LIKE ? OR username LIKE ? ORDER BY posts.post_date DESC";

      $stmt = $conn->prepare($sql);
      $searchKeyword = "%" . $query . "%";
      $stmt->bind_param("ss", $searchKeyword, $searchKeyword);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        $uniquePostIDs = [];

        while ($row = $result->fetch_assoc()) {
          $postID = $row['post_id'];

          if (in_array($postID, $uniquePostIDs)) {
            continue;
          }

          $uniquePostIDs[] = $postID;

          $username = $row['username'];
          $content = $row['content'];
          $postDate = $row['post_date'];
          $loveCount = $row['love_count'];

          echo "<a href='post_details.php?post_id=$postID' class='post-link'>";

          echo "<section class='post'>";
          echo "<div class='post-header'>";
          echo "<h2 class='username'>$username</h2>"; 
          echo "</div>"; 
    
          echo "<p class='meta'>Posted on $postDate</p>";
          echo "<div class='post-content'>";
          echo "<p id='limitedText'>$content</p>";
          echo "</div>";

          if (!empty($row['image_path'])) {
            $imagePath = htmlspecialchars($row['image_path']);
            $timestamp = time(); 
    
            echo "<div style='width: 100%; height: 400px; overflow: hidden;'>";
            echo "<img src='{$imagePath}?t={$timestamp}' alt='Post Image' class='fill-container'>";
            echo "</div>";
          }

          echo "<div class='love-section' style='display: flex; align-items: center;'>";
          echo "<a href='javascript:void(0);' onclick='updateLoveCount($postID)' class='love-button'><i class='fa-regular fa-heart'></i></a>";
          echo "<span class='love-count' id='love-count-$postID'>$loveCount</span>";
          echo "</div>";

          echo "</div>";
    
          echo "</section>";
          echo "</a>";
        }
      } else {
        echo "No results found for '$query'";
      }

      $stmt->close();
    } else {
      $sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id ORDER BY posts.post_date DESC";
      $result = $conn->query($sql);

      $uniquePostIDs = []; 
    
      if ($result === false) {
        echo "Error: " . $conn->error;
      } elseif ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          $postID = $row['post_id'];

          if (in_array($postID, $uniquePostIDs)) {
            continue;
          }

          $uniquePostIDs[] = $postID;

          $username = $row['username'];
          $content = $row['content'];
          $postDate = $row['post_date'];
          $loveCount = $row['love_count'];

          echo "<a href='post_details.php?post_id=$postID' class='post-link'>";

          echo "<section class='post'>";
          echo "<div class='post-header'>";
          echo "<h2 class='username'>$username</h2>"; 
          echo "</div>"; 
    
          echo "<p class='meta'>Posted on $postDate</p>";
          echo "<div class='post-content'>";
          echo "<p class='limitedText'>$content</p>";

          if (!empty($row['image_path'])) {
            $imagePath = htmlspecialchars($row['image_path']);
            $timestamp = time(); 
    
            echo "<div style='width: 100%; height: 400px; overflow: hidden;'>";
            echo "<img src='{$imagePath}?t={$timestamp}' alt='Post Image' class='fill-container'>";
            echo "</div>";
          }

          echo "<div class='love-section' style='display: flex; align-items: center;'>";
          echo "<a href='javascript:void(0);' onclick='updateLoveCount($postID)' class='love-button'><i class='fa-regular fa-heart'></i></a>";
          echo "<span class='love-count' id='love-count-$postID'>$loveCount</span>";
          echo "</div>";

          echo "</div>";
    
          echo "</section>";
          echo "</a>";
        }
      } else {
        echo "No posts found.";
      }
    }
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

    document.addEventListener("DOMContentLoaded", function () {
      var contentParagraphs = document.querySelectorAll(".limitedText");
      var maxHeight = 36; 

      contentParagraphs.forEach(function (contentParagraph) {
        if (contentParagraph.clientHeight > maxHeight) {
          contentParagraph.style.overflow = 'hidden';
          contentParagraph.style.height = maxHeight + 'px';
          contentParagraph.style.lineHeight = '1.2em'; 
        }
      });
    });
  </script>

</body>

</html>