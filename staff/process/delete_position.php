<?php
include "../../db_connection/db_connection.php"; 

if (isset($_GET['id'])) {
    $positionId = intval($_GET['id']); 

    // Prepare the DELETE statement
    $query = "DELETE FROM tbl_positions WHERE position_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $positionId); // Bind the position ID
        mysqli_stmt_execute($stmt);

        // Check if the delete was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect with success message
            header("Location: ../../staff/manage_staff.php?message=Position deleted successfully");
        } else {
            // Redirect with error message
            header("Location: ../../staff/manage_staff.php?error=Failed to delete position");
        }

        mysqli_stmt_close($stmt);
    } else {
        // Redirect with error message
        header("Location: ../../staff/manage_staff.php?error=Database query failed");
    }
} else {
    // Redirect with error message
    header("Location: ../../staff/manage_staff.php?error=No position ID specified");
}

mysqli_close($conn); // Close the database connection
?>
