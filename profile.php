<!DOCTYPE html>
<html>

<head>
  <title>My Blogging System - Profile</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    .button-container {
      display: flex;
      gap: 10px;
    }

    .button {
      padding: 10px 15px;
      background-color: rgba(0, 0, 0, 0);
      color: #000;
      border: 1px solid #000;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease, color 0.3s ease;
    }

    .button:hover {
      background-color: rgba(0, 0, 0, 0.2);
      color: black;
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
        <!-- <li><a href="profile.php">Profile</a></li>
        <li><a href="login.php" id="login-link">Log In</a></li> -->
      </ul>
    </nav>
    <span class="separator"><i class="fa-solid fa-grip-lines-vertical"></i></span>

    <div class="logout-button" onclick="location.href='logout.php'">
      <i class="fas fa-arrow-right-from-bracket"></i>
      Logout
    </div>

  </header>
  <main>
    <h2>Profile</h2>
    <section class="post">
      <div class="profile-info">
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        session_start();
        include 'connect.php';

        if (!isset($_SESSION["user_id"])) {
          exit("User not logged in.");
        }

        $user_id = $_SESSION["user_id"];

        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if (!$stmt->execute()) {
          echo "SQL Error: " . $stmt->error;
          exit;
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          $row = $result->fetch_assoc();
          $username = $row["username"];
          $email = $row["email"];
          $profile_image = $row["profile_image"];

          echo '<div class="profile-details">';
          echo '<h3>Username: ' . $username . '</h3>';
          echo '<p>Email: ' . $email . '</p>';

          echo '<div class="form-group">';
          echo '<div class="button-container">';
          echo '<a href="editprofile.html"><button class="button">Edit</button></a>';
          echo '<a href="logout.php"><button class="button">Logout</button></a>';
          echo '</div>';
          echo '</div>';
          echo '</div>';

          echo '<div class="profile-image-container">';
          if (isset($profile_image) && !empty($profile_image) && file_exists($profile_image)) {
            echo '<img src="' . $profile_image . '" alt="Profile Image" class="profile-image" onclick="document.getElementById(\'image\').click();">';
          } else {
            echo '<img src="uploads/default_profile.jpg" alt="Default Profile Image" class="profile-image" onclick="document.getElementById(\'image\').click();">';
          }
          echo '<form action="profile.php" method="post" enctype="multipart/form-data" id="imageForm">';
          echo '<input type="file" name="image" id="image" accept="image/*" style="display: none;" onchange="readURL(this);">';
          echo '<input type="submit" value="Upload Image" name="submitImage">';
          echo '</form>';
          echo '<button type="button" onclick="document.getElementById(\'image\').click();">Upload Image</button>';
          echo '</div>';
        } else {
          exit("User not found.");
        }

        $stmt->close();

        if (isset($_FILES['image'])) {
          $target_dir = "uploads/";
          $target_file = $target_dir . basename($_FILES["image"]["name"]);
          $uploadOk = 1;
          $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

          if (file_exists($target_file)) {
            $filename = pathinfo($target_file, PATHINFO_FILENAME);
            $extension = pathinfo($target_file, PATHINFO_EXTENSION);
            $timestamp = time();
            $target_file = $target_dir . $filename . '_' . $timestamp . '.' . $extension;
          }

          if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
          } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
              $updateSql = "UPDATE users SET profile_image = ? WHERE user_id = ?";
              $updateStmt = $conn->prepare($updateSql);
              $updateStmt->bind_param("si", $target_file, $user_id);

              if ($updateStmt->execute()) {
                header("Location: profile.php");
                exit();
              } else {
                echo "Error updating profile image.";
              }
            }
          }
        }

        $conn->close();
        ?>
      </div>
    </section>
  </main>
  <script>
  function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        document.getElementById('profileImage').src = e.target.result;
      };

      reader.readAsDataURL(input.files[0]);
    }
  }

  function uploadImage() {
    document.getElementById('imageForm').submit();
  }

  document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("image").addEventListener("change", function () {
      uploadImage();
    });

    document.getElementById("profileImage").addEventListener("click", function () {
      document.getElementById("image").click();
    });
  });
</script>



  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>

</body>

</html>
