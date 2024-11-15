<?php
session_start(); // Start the session to access stored username

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['username'])) {
    // Sanitize input
    $new_password = htmlspecialchars($_POST['new_password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Connect to the database
        $conn = new mysqli('localhost', 'root', '', 'bwsdb');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Update the user's password in the database
        $username = $_SESSION['username'];
        $query = "UPDATE tbl_users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $hashed_password, $username);
        if ($stmt->execute()) {
            echo '<p>Password has been reset successfully.</p>';
        } else {
            echo '<p>Error resetting password. Please try again.</p>';
        }

        // Close the database connection
        $stmt->close();
        $conn->close();

        // Clear the session variables
        unset($_SESSION['otp']);
        unset($_SESSION['username']);
    } else {
        echo '<p>Passwords do not match. Please try again.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}
?>
