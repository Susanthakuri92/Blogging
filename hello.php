<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "canteenmate";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"]  == "POST") {
    // Sign up functionality
    if (isset($_POST["signup"])) {
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirm_password"];

        // Perform necessary validation checks
        if ($password !== $confirmPassword) {
            echo "Passwords do not match.";
            // You can also redirect the user back to the sign-up page or display an error message.
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
            echo "User registered successfully.";
        } else {
            echo "Error occurred while registering the user.";
        }

        $stmt->close();
    }

    // Login functionality
    if (isset($_POST["login"])) {
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
                echo "Login successful.";
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "User not found.";
        }

        $stmt->close();
    }
 // Retrieve data functionality
 if (isset($_POST["retrieve_data"])) {
    // Perform database query
    $sql = "SELECT * FROM your_table";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Loop through each row
        while ($row = $result->fetch_assoc()) {
            // Access the data from the row
            $id = $row["id"];
            $name = $row["name"];
            $email = $row["email"];

            // Process or display the data as needed
            echo "ID: " . $id . ", Name: " . $name . ", Email: " . $email . "<br>";
        }
    } else {
        echo "No results found.";
    }
}
}

$conn->close();
?>