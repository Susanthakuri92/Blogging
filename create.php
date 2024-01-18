<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Include the database connection file
include 'connect.php';

// Check if the user is not logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page
    header("Location: login.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $content = trim($_POST['content']); // Trim whitespace from the content

    // Check if the content is empty
    if (empty($content)) {
        die("Please enter content for your post.");
    }

    // Handle image upload
    $target_dir = "uploads/";

    // Ensure the target directory exists
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Get the MIME type using getimagesize
$imageInfo = @getimagesize($_FILES["image"]["tmp_name"]);

// Check if the file is an image
if ($imageInfo === false) {
    die("File is not a valid image.");
}

// Check file size
if ($_FILES["image"]["size"] > 500000) {
    die("Sorry, your file is too large.");
}

// Allow certain file formats
$allowedFormats = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowedFormats)) {
    die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    die("Sorry, your file was not uploaded.");
} else {
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["image"]["name"])) . " has been uploaded.";

        // Prepare the SQL statement
        $sql = "INSERT INTO posts (user_id, content, image_path, post_date) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);

        // Bind the parameters and execute the statement
        $stmt->execute([$_SESSION["user_id"], $content, $target_file]);

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            // Successful insertion, redirect to the index page
            header('Location: /index.php');
            exit();
        } else {
            // Log detailed error information
            error_log("Error saving post: " . $stmt->error);

            // Inform the user about the error
            die("Error saving post. Please try again later.");
        }
    } else {
        // Print detailed error information
        die("Sorry, there was an error uploading your file.<br>Error details: " . $_FILES["image"]["error"]);
    }
}
}

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- ... (your existing head content) ... -->
</head>

<body>
    <!-- ... (your existing body content) ... -->
</body>

</html>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blogging System - Create Post</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
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
            width: 99%;
            padding: 8px;
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
    </style>
</head>

<body>
    <header>
        <h1><a href="index.php" style="text-decoration: none; color: inherit;">My Blogging System</a></h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="create.php">Create Post</a></li>
                <?php
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
        <section class="post">
            <h2>Create Post</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="content">Content:</label>
                    <textarea id="content" name="content" required></textarea>
                </div>
                <div class="form-group">
                <label for="image">Select Image:</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>
            <div class="form-group">
                <button type="submit">Publish Post</button>
            </div>
        </form>
    </section>
</main>
</body>
</html>
