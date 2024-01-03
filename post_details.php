<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Details</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <!-- Add the Font Awesome CSS link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .comment-form-container {
            background-color: #e5e3e3;
            border-radius: 18px;
            padding: 10px;
            margin-top: 10px;
        }

        .comment-form-container h3 {
            font-size: 1.2em;
            margin-bottom: 10px;
        }

        .comment-container {
            display: flex;
        }

        .form-group {
            margin-bottom: 15px;
        }

        form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        textarea,
        input[type="file"] {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 3px;
            margin-bottom: 10px;
        }

        button[type="submit"] {
            padding: 8px 15px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .user-info-container {
            display: flex;
            align-items: center;
            /* Align items vertically */
        }

        .username {
            margin: 0;
        }

        .button-container {
            margin-left: auto;
            /* Push the button container to the right */
            display: flex;
            gap: 10px;
        }

        .action-button {
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

        section.post {
            background-color: #f8f6f6;
            padding: 9px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            border-radius: 5px;
            margin: 20px auto;
            /* Center the post container horizontally */
            position: relative !important;
            width: auto;
            max-width: 100%;
            /* Allow the post box to be as wide as the viewport */
        }

        .post-content {
            margin-bottom: 10px;
            /* Add some space between content and image */
        }

        .fill-container {
            width: 100%;
            height: 600px;
            /* Set a fixed height for the container */
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .fill-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Maintain aspect ratio and cover the container */
        }

        /* Responsive styles */
        @media (min-width: 768px) {
            section.post {
                min-width: 300px;
                /* Adjust this value based on your design */
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

        // Check if the delete parameter is set
        if (isset($_GET['delete'])) {
            $deletePostID = $_GET['post_id'];

            // Fetch the post from the database to get the image path
            $sqlSelect = "SELECT image_path FROM posts WHERE post_id = ?";
            $stmtSelect = $conn->prepare($sqlSelect);
            $stmtSelect->bind_param("i", $deletePostID);
            $stmtSelect->execute();
            $resultSelect = $stmtSelect->get_result();

            if ($resultSelect->num_rows > 0) {
                $rowSelect = $resultSelect->fetch_assoc();
                $imagePathToDelete = $rowSelect['image_path'];

                // Delete the post from the database
                $sqlDelete = "DELETE FROM posts WHERE post_id = ?";
                $stmtDelete = $conn->prepare($sqlDelete);
                $stmtDelete->bind_param("i", $deletePostID);

                if ($stmtDelete->execute()) {
                    // Remove the associated image file
                    if (!empty($imagePathToDelete) && file_exists($imagePathToDelete)) {
                        unlink($imagePathToDelete);
                    }
                    // Redirect back to the post listing after deletion
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

        // Check if the post ID is provided
        if (isset($_GET['post_id'])) {
            $postID = $_GET['post_id'];

            // Check if the love parameter is set
            if (isset($_GET['love'])) {
                $lovePostID = $_GET['love'];

                // Validate user permissions or any other checks before updating love count
                // Use prepared statement to avoid SQL injection
                $sqlUpdateLove = "UPDATE posts SET love_count = love_count + 1 WHERE post_id = ?";
                $stmtUpdateLove = $conn->prepare($sqlUpdateLove);
                $stmtUpdateLove->bind_param("i", $lovePostID);

                if ($stmtUpdateLove->execute()) {
                    // Redirect back to the post details page
                    echo "<script>window.location.href = 'post_details.php?post_id={$postID}';</script>";
                    die();
                } else {
                    echo "Error updating love count: " . $stmtUpdateLove->error;
                }

                $stmtUpdateLove->close();
            }

            // Fetch the post from the database
            $sql = "SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.user_id WHERE post_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $postID);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the post exists
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

                $comments = getComments($postID); // Assuming this function fetches comments
        
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

        // Display the edit form if the edit button is clicked
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
                echo "<form action='' method='post' enctype='multipart/form-data'>";
                echo "<input type='hidden' name='post_id' value='{$postID}'>";
                echo "<label>Content:</label><br>";
                echo "<textarea name='content'>{$content}</textarea><br>";

                // Display the current image
                if (!empty($row['image_path'])) {
                    echo "<img src='" . escape($row['image_path']) . "' alt='Current Post Image' class='fill-container'><br>";
                }

                // Input for uploading a new image
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

            // Close the statement
            $stmt->close();
        }

        // Check if the form is submitted for editing a post
        if (isset($_POST['edit_post'])) {
            $postID = $_POST['post_id'];
            $content = $_POST['content'];

            // Update the post in the database
            $sql = "UPDATE posts SET content = ?, image_path = ? WHERE post_id = ?";
            $stmt = $conn->prepare($sql);

            // Check if a new image is uploaded
            $newImagePath = handleImageUpload($postID);

            // Always bind both parameters but adjust SQL query accordingly
            if (!empty($newImagePath)) {
                // If a new image is uploaded
                $stmt->bind_param("ssi", $content, $newImagePath, $postID);
            } else {
                // If no new image, bind parameters without image path
                // Fetch the existing image path from the database
                $sqlImagePath = "SELECT image_path FROM posts WHERE post_id = ?";
                $stmtImagePath = $conn->prepare($sqlImagePath);
                $stmtImagePath->bind_param("i", $postID);
                $stmtImagePath->execute();
                $resultImagePath = $stmtImagePath->get_result();

                if ($resultImagePath->num_rows > 0) {
                    $rowImagePath = $resultImagePath->fetch_assoc();
                    $existingImagePath = $rowImagePath['image_path'];

                    // Bind parameters without image path
                    $stmt->bind_param("ssi", $content, $existingImagePath, $postID);
                } else {
                    echo "Error fetching existing image path.";
                    exit();
                }

                $stmtImagePath->close();
            }

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect back to the post details page
                echo "<script>window.location.href = 'post_details.php?post_id={$postID}';</script>";
                die();
            } else {
                echo "Error updating post: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        }



        // Close the database connection
        $conn->close();

        // Function to handle image upload
        function handleImageUpload($postID)
        {
            $targetDir = "uploads/";
            $targetFile = $targetDir . basename($_FILES["new_image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if a file is selected for upload
            if (empty($_FILES["new_image"]["tmp_name"])) {
                echo "No new image selected. ";
                return "";
            }

            // Check file size
            if ($_FILES["new_image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                // If everything is ok, try to upload the file
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
            // Implement your logic for handling comment submission here if needed
            // Redirect to the current page after form submission
            window.location.href = `post_details.php?post_id=${postID}`;
            return true; // Prevent form submission (you can remove this line if you want the form to submit)
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
    </script>


</body>

</html>