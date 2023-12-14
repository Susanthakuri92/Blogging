<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
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
