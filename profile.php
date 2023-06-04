<!DOCTYPE html>
<html>
<head>
  <title>My Blogging System - Profile</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <style>
profile-main {
  text-align: center;
}

.profile-info {
  background-color: #f5f5f5;
  padding: 20px;
  border-radius: 5px;
  margin-top: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.profile-info h3 {
  color: #333;
  font-size: 24px;
  margin-bottom: 10px;
  margin-top: 0;
}

.profile-info p {
  color: #777;
  margin-bottom: 5px;
}

.profile-info p:first-child {
  margin-top: 0;
}

.profile-info p:last-child {
  margin-bottom: 0;
}
</style>
</head>
<body>
  <header>
    <h1>My Blogging System</h1>
    <nav>
      <ul>
        <li><a href="index.html">Home</a></li>
        <li><a href="create.html">Create Post</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="login.html" id="login-link">Log In</a></li>
      </ul>
    </nav>
  </header>
  <main>
    <h2>Profile</h2>
    <div class="profile-info">
      <?php
      session_start();
      include 'connect.php';

      if (!isset($_SESSION["user_id"])) {
          // Handle the case if the user is not logged in
          // You can redirect or display an error message
          exit("User not logged in.");
      }

      $user_id = $_SESSION["user_id"];

      // Fetch user details from the database
      $sql = "SELECT * FROM users WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("i", $user_id);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
          $row = $result->fetch_assoc();
          $username = $row["username"];
          $email = $row["email"];
          $bio = $row["bio"];

          // Display the user profile information
          echo '<h3>Username: ' . $username . '</h3>';
          echo '<p>Email: ' . $email . '</p>';
          echo '<p>Bio: ' . $bio . '</p>';
          echo '<div class="form-group">';
          echo '<a href="editprofile.html"><button>Edit</button></a>';
          echo '</div>';
      } else {
          // Handle the case if the user is not found
          // You can redirect or display an error message
          exit("User not found.");
      }

      $stmt->close();
      $conn->close();
      ?>
    </div>
  </main>
  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>
</body>
</html>
