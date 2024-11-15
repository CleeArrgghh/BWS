<?php
// Include database connection
include '../../db_connection/db_connection.php';

// Enable error reporting for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user ID is set in the URL
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']); // Ensure it's an integer

    // Prepare a DELETE statement
    $sql = "DELETE FROM tbl_users WHERE user_id = ?";
    
    // Initialize prepared statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id); // Bind the parameter
        if ($stmt->execute()) {
            // Redirect back to the user data page with a success message
            header("Location: view_all_users.php");
            exit();
        } else {
            echo "Error deleting user: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "No user ID specified.";
}

// Close the database connection
$conn->close();
?>
