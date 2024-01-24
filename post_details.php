<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/post_details.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <h1><a href="index.php" style="text-decoration: none; color: inherit;">My Blogging System</a></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="create.php">Create Post</a></li>
                <!-- <li><a href="profile.php">Profile</a></li>
                <li><a href="login.php" id="login-link">Log In</a></li> -->
                <?php
        session_start();
        if (isset($_SESSION["user_id"])) {
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
          echo '<li><a href="profile.php"><img src="' . (isset($profile_image) ? $profile_image : 'uploads/default_profile.jpg') . '" alt="Profile Image" class="profile-image-nav"></a></li>';
        } else {
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
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
        include 'connect.php';
        include 'comments.php';

        function escape($str)
        {
            return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        }

        function confirmDeleteScript($postID)
        {
            return "if (confirm('Are you sure you want to delete this post?')) { window.location.href = `post_details.php?post_id={$postID}&delete={$postID}`; }";
        }

        function handleCommentSubmitScript($postID)
        {
            return "window.location.href = `post_details.php?post_id={$postID}`;";
        }

        function handleActionScript($action, $postID)
        {
            if ($action === 'delete') {
                return confirmDeleteScript($postID);
            } elseif ($action === 'love') {
                return "window.location.href = `post_details.php?post_id={$postID}&love={$postID}`;";
            }
        }

        function showEditFormScript($postID)
        {
            return "window.location.href = `post_details.php?edit={$postID}`;";
        }
        if (isset($_GET['delete'])) {
            $deletePostID = $_GET['post_id'];

            $sqlSelect = "SELECT image_path FROM posts WHERE post_id = ?";
            $stmtSelect = $conn->prepare($sqlSelect);
            $stmtSelect->bind_param("i", $deletePostID);
            $stmtSelect->execute();
            $resultSelect = $stmtSelect->get_result();

            if ($resultSelect->num_rows > 0) {
                $rowSelect = $resultSelect->fetch_assoc();
                $imagePathToDelete = $rowSelect['image_path'];

                $sqlDelete = "DELETE FROM posts WHERE post_id = ?";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->bind_param("i", $deletePostID);

                if ($stmtDelete->execute()) {
                                        if (!empty($imagePathToDelete) && file_exists($imagePathToDelete)) {
                        unlink($imagePathToDelete);
                    }
                    echo "<script>window.location.href = 'index.php';</script>";
                    die();
                } else {
                    echo "Error deleting post: " . $stmtDelete->error;
                }

                $stmtDelete->close();
            } else {
                echo "Post not found.";
            }

            $stmtSelect->close();
        }

        if (isset($_GET['post_id'])) {
            $postID = $_GET['post_id'];

            if (isset($_GET['love'])) {
                $lovePostID = $_GET['love'];

                $sqlUpdateLove = "UPDATE posts SET love_count = love_count + 1 WHERE post_id = ?";
                $stmtUpdateLove = $conn->prepare($sqlUpdateLove);
                $stmtUpdateLove->bind_param("i", $lovePostID);

                if ($stmtUpdateLove->execute()) {
                    echo "<script>window.location.href = 'post_details.php?post_id={$postID}';</script>";
                    die();
                } else {
                    echo "Error updating love count: " . $stmtUpdateLove->error;
                }

                $stmtUpdateLove->close();
            }

            $sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $postID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $username = $row['username'];
                $content = $row['content'];
                $postDate = $row['post_date'];
                $loveCount = $row['love_count'];

                echo "<section class='post'>";
                echo "<div class='user-info-container'>";
                echo "<h2 class='username'>$username</h2>";

                echo "<div class='button-container'>";
                echo "<div class='share-container'>";
    echo "<div class='action-button share' id='shareButton'>Share</div>";

    echo "<div class='share-options' id='shareOptions'>";
    echo "<div class='a2a_kit a2a_kit_size_32 a2a_default_style'>";
    echo "<a class='a2a_dd' href='https://www.addtoany.com/share'></a>";
    echo "<a class='a2a_button_facebook' href='https://www.facebook.com/sharer/sharer.php?u=" . urlencode("https://yourdomain.com/post_details.php?post_id=$postID") . "'></a>";
    echo "<a class='a2a_button_facebook_messenger' href='https://www.facebook.com/dialog/send?link=" . urlencode("https://yourdomain.com/post_details.php?post_id=$postID") . "'></a>";
    echo "<a class='a2a_button_linkedin' href='https://www.linkedin.com/shareArticle?url=" . urlencode("https://yourdomain.com/post_details.php?post_id=$postID") . "'></a>";
    echo "<a class='a2a_button_whatsapp' href='whatsapp://send?text=" . urlencode("Check out this post: https://yourdomain.com/post_details.php?post_id=$postID") . "'></a>";
    echo "</div>";
    echo "</div>";
    echo "</div>";

                echo '<script async src="https://static.addtoany.com/menu/page.js"></script>';
                echo "<button onclick=\"" . confirmDeleteScript($postID) . "\" class='action-button'>Delete</button>";
                echo "<button onclick=\"" . showEditFormScript($postID) . "\" class='action-button'>Edit</button>";

                echo "</div>";

                echo "</div>";

                echo "<p class='meta'>Posted on $postDate</p>";
                echo "<div class='post-content'>";
                echo "<p>" . escape($content) . "</p>";

                if (!empty($row['image_path'])) {
                    echo "<img src='" . escape($row['image_path']) . "' alt='Current Post Image' class='fill-container'><br>";
                }

                echo "<div class='love-section' style='display: flex; align-items: center;'>";
                echo "<a href='post_details.php?post_id={$postID}&love={$postID}' class='love-button'><i class='fa-regular fa-heart'></i></a>";
                echo "<span class='love-count'>$loveCount</span>";
                echo "</div>";

                $comments = getComments($postID); 
        
                if (!empty($comments)) {
                    echo "<div class='comment-form-container'>";
                    echo "<h3>Comments</h3>";
                    foreach ($comments as $comment) {
                        echo "<div class='comment-container'>";
                        echo "<p class='comment-user'>{$comment['user_name']}: </p>";
                        echo "<p class='comment-text'>{$comment['comment_text']}</p>";
                        echo "</div>";
                    }
                    echo "</div>";
                }

                echo "<form action='comments.php' method='post' onsubmit=\"" . handleCommentSubmitScript($postID) . "\">";
                echo "<input type='hidden' name='postID' value='{$postID}'>";
                echo "<div class='form-group'>";
                echo "<label for='comment'>Add a Comment:</label>";
                echo "<textarea id='comment' name='comment' required></textarea>";
                echo "</div>";
                echo "<div class='form-group'>";
                echo "<button type='submit' name='submitComment' class='comment-submit-btn'>Submit Comment</button>";
                echo "</div>";
                echo "</form>";
                echo "</div>";

                echo "</section>";

                $stmt->close();
            } else {
                echo "Post not found.";
            }
        }

        if (isset($_GET['edit'])) {
            $postID = $_GET['edit'];

            $sql = "SELECT * FROM posts WHERE post_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $postID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $content = $row['content'];

                echo "<h2>Edit Post</h2>";
                echo "<section class='post'>";

                echo "<form action='' method='post' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='post_id' value='{$postID}'>";
                echo "<label>Content:</label><br>";
                echo "<textarea name='content'>{$content}</textarea><br>";

                if (!empty($row['image_path'])) {
                    echo "<img src='" . escape($row['image_path']) . "' alt='Current Post Image' class='fill-container'><br>";
                }

                echo "<label for='new_image'>Upload a New Image:</label>";
                echo "<input type='file' name='new_image'><br>";

                echo "<div class='button-container'>";
                echo "<input type='submit' name='edit_post' value='Save' class='action-button'>";
                echo "</div>";
                echo "</form>";

                echo "</section>";
            } else {
                echo "Post not found.";
            }

            $stmt->close();
        }

        if (isset($_POST['edit_post'])) {
            $postID = $_POST['post_id'];
            $content = $_POST['content'];

            $sql = "UPDATE posts SET content = ?, image_path = ? WHERE post_id = ?";
            $stmt = $conn->prepare($sql);

            $newImagePath = handleImageUpload($postID);

            if (!empty($newImagePath)) {
                $stmt->bind_param("ssi", $content, $newImagePath, $postID);
            } else {
                $sqlImagePath = "SELECT image_path FROM posts WHERE post_id = ?";
                $stmtImagePath = $conn->prepare($sqlImagePath);
                $stmtImagePath->bind_param("i", $postID);
                $stmtImagePath->execute();
                $resultImagePath = $stmtImagePath->get_result();

                if ($resultImagePath->num_rows > 0) {
                    $rowImagePath = $resultImagePath->fetch_assoc();
                    $existingImagePath = $rowImagePath['image_path'];

                    $stmt->bind_param("ssi", $content, $existingImagePath, $postID);
                } else {
                    echo "Error fetching existing image path.";
                    exit();
                }

                $stmtImagePath->close();
            }

            if ($stmt->execute()) {
                echo "<script>window.location.href = 'post_details.php?post_id={$postID}';</script>";
                die();
            } else {
                echo "Error updating post: " . $stmt->error;
            }

            $stmt->close();
        }

        $conn->close();

        function handleImageUpload($postID)
        {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["new_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            if (empty($_FILES["new_image"]["tmp_name"])) {
                echo "No new image selected. ";
                return "";
            }

            if ($_FILES["new_image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["new_image"]["tmp_name"], $targetFile)) {
                    echo "The file " . basename($_FILES["new_image"]["name"]) . " has been uploaded.";
                    return $targetFile;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    return "";
                }
            }
        }
        ?>
    </main>
    <footer>
        <p>&copy; 2023 My Blogging System. All rights reserved.</p>
    </footer>
    <script>
        function confirmDelete(postID) {
            if (confirm("Are you sure you want to delete this post?")) {
                window.location.href = `post_details.php?post_id=${postID}&delete=${postID}`;
            }
        }

        function handleCommentSubmit(postID) {
            window.location.href = `post_details.php?post_id=${postID}`;
            return true; 
        }

        function handleAction(action, postID) {
            if (action === 'delete') {
                if (confirm("Are you sure you want to delete this post?")) {
                    window.location.href = `post_details.php?post_id=${postID}&delete=${postID}`;
                }
            } else if (action === 'love') {
                window.location.href = `post_details.php?post_id=${postID}&love=${postID}`;
            }
        }

        function showEditForm(postID) {
            window.location.href = `post_details.php?edit=${postID}`;
        }
        var shareOptions = document.getElementById('shareOptions');

        document.getElementById('shareButton').addEventListener('click', function () {
            shareOptions.style.display = (shareOptions.style.display === 'block') ? 'none' : 'block';
        });

        document.addEventListener('click', function (event) {
            if (!shareOptions.contains(event.target) && event.target !== document.getElementById('shareButton')) {
                shareOptions.style.display = 'none';
            }
        });
    </script>


</body>

</html>