<?php
include "../../db_connection/db_connection.php"; 


// Check if staff ID is provided
if (isset($_GET['id'])) {
    $staffId = intval($_GET['id']); 

    // Prepare the DELETE statement for tbl_staff
    $query = "DELETE FROM tbl_staff WHERE staff_id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $staffId); // Bind the staff ID
        mysqli_stmt_execute($stmt);

        // Check if the delete was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect with success message
            header("Location: ../../staff/manage_staff.php");
        } else {
            // Redirect with error message
            header("Location: ../../staff/manage_staff.php");
        }

        mysqli_stmt_close($stmt);
    } else {
        // Redirect with error message
        header("Location: ../../staff/manage_staff.php");
    }
} else {
    // Redirect with error message
    header("Location: ../../staff/manage_staff.php");
}

// Close the database connection
mysqli_close($conn); 
?>
