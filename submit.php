<?php
// Include the database connection file
include 'connect.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the form data
  $title = $_POST['title'];
  $content = $_POST['content'];

  // Prepare the SQL statement
  $sql = "INSERT INTO posts (title, content) VALUES (?, ?)";
  $stmt = $conn->prepare($sql);

  // Bind the parameters and execute the statement
  $stmt->bind_param("ss", $title, $content);
  $stmt->execute();

  // Check if the insertion was successful
  if ($stmt->affected_rows > 0) {
    echo '<script>';
    echo 'document.addEventListener("DOMContentLoaded", function() {';
    echo '  document.body.style.animation = "fadeIn 0.1s ease-in-out";'; // Adjust the animation duration
    echo '  setTimeout(function() {';
    echo '    window.location.href = "index.html";';
    echo '  }, 50);'; // Delay for 1 second before redirecting
    echo '});';
    echo '</script>';
  } else {
    echo "Error saving post.";
  }

  // Close the statement
  $stmt->close();
}

// Close the database connection
$conn->close();
?>
