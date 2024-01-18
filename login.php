<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="css/styles.css">
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
          // Assuming you have the $profile_image variable available
          // Initialize $profile_image based on the user's data from the database
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

          // Display profile image if available, otherwise use a default image
          echo '<li><a href="profile.php"><img src="' . (isset($profile_image) ? $profile_image : 'uploads/default_profile.jpg') . '" alt="Profile Image" class="profile-image-nav"></a></li>';
        } else {
          // Display login link if the user is not logged in
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
  <div class="login-container">
    <h2>Log In</h2>
    <form action="login.php" method="POST">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <button type="submit">Log In</button>
      </div>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
  </div>
</main>

  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>
  
  <?php
  session_start();
  include 'connect.php';

  // Check if the user is logged in
if (isset($_SESSION["user_id"])) {
  // User is already logged in, redirect to profile.php
  header("Location: profile.php");
  exit;
}

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $username = $_POST["username"];
      $password = $_POST["password"];

      // Perform database query
      $sql = "SELECT * FROM users WHERE username = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
          $row = $result->fetch_assoc();
          if (password_verify($password, $row["password"])) {
              $_SESSION["user_id"] = $row["user_id"];
              header("Location: profile.php"); // Redirect to profile.php
              exit;
          } else {
              echo "Invalid password.";
          }
      } else {
          echo "User not found.";
      }

      $stmt->close();
  }

  $conn->close();
  ?>
</body>
</html>
