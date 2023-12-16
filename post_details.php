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
        <h1>My Blogging System</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="create.php">Create Post</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="login.php">Log In</a></li>
            </ul>
        </nav>
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

// Check if the delete parameter is set
if (isset($_GET['delete'])) {
    $deletePostID = $_GET['post_id'];

    // Validate user permissions or any other checks before deleting
    // For example, you might want to check if the user is the author of the post

    // Use prepared statement to avoid SQL injection
    $sqlDelete = "DELETE FROM posts WHERE post_id = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $deletePostID);

    if ($stmtDelete->execute()) {
        // Redirect back to the post listing after deletion
        header('Location: index.php');
        exit();
    } else {
        echo "Error deleting post: " . $stmtDelete->error;
    }

    $stmtDelete->close();
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
            header("Location: post_details.php?post_id=$postID");
            exit();
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
        echo "<button onclick=\"confirmDelete($postID)\" class='action-button'>Delete</button>";
        echo "<button onclick=\"showEditForm($postID)\" class='action-button'>Edit</button>";

        echo "</div>";

        echo "</div>";

        echo "<p class='meta'>Posted on $postDate</p>";
        echo "<div class='post-content'>";
        echo "<p>" . escape($content) . "</p>";

        if (!empty($row['image_path'])) {
            echo "<img src='" . escape($row['image_path']) . "' alt='Current Post Image' class='fill-container'><br>";
        }

        echo "<div class='love-section' style='display: flex; align-items: center;'>";
        echo "<a href='post_details.php?post_id=$postID&love=$postID' class='love-button'><i class='fa-regular fa-heart'></i></a>";
        echo "<span class='love-count'>$loveCount</span>";
        echo "</div>";

        $comments = getComments($postID);
        if (!empty($comments)) {
            echo "<div class='comments-container'>";
            echo "<h3>Comments</h3>";
            echo "<ul>";
            foreach ($comments as $comment) {
                echo "<li>" . escape("{$comment['user_name']}: {$comment['comment_text']}") . "</li>";
            }
            echo "</ul>";
            echo "</div>";
        } else {
            echo "<p>No comments yet.</p>";
        }

        echo "<div class='comment-form'>";
        echo "<h3>Add a Comment</h3>";
        echo "<form action='' method='post'>";
        echo "<input type='hidden' name='postID' value='$postID'>";
        echo "<div class='form-group'>";
        echo "<label for='comment'>Your Comment:</label>";
        echo "<textarea id='comment' name='comment' required></textarea>";
        echo "</div>";
        echo "<div class='form-group'>";
        echo "<button type='submit' name='submitComment'>Submit Comment</button>";
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
        echo "<input type='hidden' name='post_id' value='$postID'>";
        echo "<label>Content:</label><br>";
        echo "<textarea name='content'>$content</textarea><br>";

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

    // Handle image upload
    $newImagePath = handleImageUpload($postID);

    // Update the post in the database
    $sql = "UPDATE posts SET content = ?, image_path = ? WHERE post_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $content, $newImagePath, $postID);

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

// Close the database connection
$conn->close();

// Function to handle image upload
function handleImageUpload($postID)
{
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["new_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["new_image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check file size
    if ($_FILES["new_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. Your file has an extension of $imageFileType.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
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


        function showEditForm(postID) {
            window.location.href = `post_details.php?edit=${postID}`;
        }
    </script>
</body>

</html>