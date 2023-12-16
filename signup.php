<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

  <div class="signup-container">
    <h2>Sign Up</h2>
    <form action="signup.php" method="POST">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" name="confirm_password" required>
      </div>
      <div class="form-group">
        <button type="submit" name="signup">Sign Up</button>
      </div>
    </form>

    <p>Already have an account? <a href="login.html">Log in</a></p>
  </div>

  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>
  
  <?php
  session_start();
  include 'connect.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
      $username = $_POST["username"];
      $email = $_POST["email"];
      $password = $_POST["password"];
      $confirmPassword = $_POST["confirm_password"];

      // Perform necessary validation checks
      if ($password !== $confirmPassword) {
          echo "Passwords do not match.";
          exit;
      }

      // Hash the password
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // Perform database query
      $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sss", $username, $email, $hashed_password);
      $stmt->execute();

      if ($stmt->affected_rows == 1) {
          header("Location: login.php");
          exit;
      } else {
          echo "Error occurred while registering the user.";
      }

      $stmt->close();
  }

  $conn->close();
  ?>
</body>
</html>
