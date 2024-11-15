<?php
// Ensure the correct path to your database connection file
include '../../db_connection/db_connection.php'; // Adjust this path as needed



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the $conn variable is defined
    if (!isset($conn)) {
        die("Database connection not established.");
    }

    // Use mysqli_real_escape_string safely
    $position_name = mysqli_real_escape_string($conn, $_POST['positionName']);
    $description = mysqli_real_escape_string($conn, $_POST['positionDescription']);

    // Prepare the SQL statement
    $query = "INSERT INTO tbl_positions (position_name, description) VALUES ('$position_name', '$description')";
    
    // Execute the query
    if (mysqli_query($conn, $query)) {
        header("Location: ../../staff/manage_staff.php? succesful inserted data.");
        exit; // Ensure no further code is executed after redirection
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }
}
?>
