<!DOCTYPE html>
<html>

<head>
  <title>My Blogging System - Profile</title>
  <link rel="stylesheet" type="text/css" href="styles.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

  <style>
    .button-container {
      display: flex;
      gap: 10px;
    }

    .button {
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

    .button:hover {
      background-color: rgba(0, 0, 0, 0.2);
      /* Semi-transparent black on hover */
      color: black;
      /* White text on hover */
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
    <h2>Profile</h2>
    <section class="post">
      <div class="profile-info">
        <?php
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        session_start();
        include 'connect.php';

        if (!isset($_SESSION["user_id"])) {
          // Handle the case if the user is not logged in
          // You can redirect or display an error message
          exit("User not logged in.");
        }

        $user_id = $_SESSION["user_id"];

        // Fetch user details from the database using a prepared statement
        $sql = "SELECT * FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);

        if (!$stmt->execute()) {
          // Display SQL error if the query execution fails
          echo "SQL Error: " . $stmt->error;
          exit; // Exit the script
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
          $row = $result->fetch_assoc();
          $username = $row["username"];
          $email = $row["email"];

          // Display the user profile information
          echo '<h3>Username: ' . $username . '</h3>';
          echo '<p>Email: ' . $email . '</p>';

          echo '<div class="form-group">';
          echo '<div class="button-container">';
          echo '<a href="editprofile.html"><button class="button">Edit</button></a>';
          echo '<a href="logout.php"><button class="button">Logout</button></a>';
          echo '</div>';
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
    </section>
  </main>
  <footer>
    <p>&copy; 2023 My Blogging System. All rights reserved.</p>
  </footer>
</body>

</html>