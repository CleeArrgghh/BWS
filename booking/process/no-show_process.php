<?php
// Include your database connection file
include '../../db_connection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'];

    if (isset($_POST['resolve'])) {
        // SQL to move the booking back to approved_bookings table
        $sql_select = "SELECT * FROM no_show_bookings WHERE appointment_id = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $appointment_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Insert the record back into approved_bookings table
            $sql_insert = "INSERT INTO approved_bookings (appointment_id, full_name, service_names, appointment_date, appointment_time, total_price) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param(
                "issssd",
                $row['appointment_id'],
                $row['full_name'],
                $row['service_names'],
                $row['appointment_date'],
                $row['appointment_time'],
                $row['total_price']
            );

            if ($stmt_insert->execute()) {
                // Delete the record from no_show_bookings
                $sql_delete_no_show = "DELETE FROM no_show_bookings WHERE appointment_id = ?";
                $stmt_delete = $conn->prepare($sql_delete_no_show);
                $stmt_delete->bind_param("i", $appointment_id);
                $stmt_delete->execute();

                echo "Booking marked as resolved and moved back to approved bookings successfully.";
            } else {
                echo "Error inserting record: " . $conn->error;
            }
        } else {
            echo "No no-show booking found with the given ID.";
        }

    } elseif (isset($_POST['delete'])) {
        // SQL to delete the booking
        $sql_delete = "DELETE FROM no_show_bookings WHERE appointment_id = ?";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bind_param("i", $appointment_id);

        if ($stmt->execute()) {
            echo "Booking deleted successfully.";
        } else {
            echo "Error deleting record: " . $conn->error;
        }

    } elseif (isset($_POST['no_show'])) {
        // SQL to move the booking to the no_show_bookings table
        $sql_select = "SELECT * FROM approved_bookings WHERE appointment_id = ?";
        $stmt_select = $conn->prepare($sql_select);
        $stmt_select->bind_param("i", $appointment_id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Insert the record into no_show_bookings table
            $sql_insert = "INSERT INTO no_show_bookings (appointment_id, full_name, service_names, appointment_date, appointment_time, total_price) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param(
                "issssd",
                $row['appointment_id'],
                $row['full_name'],
                $row['service_names'],
                $row['appointment_date'],
                $row['appointment_time'],
                $row['total_price']
            );

            if ($stmt_insert->execute()) {
                // Optionally delete the record from approved_bookings
                $sql_delete_approved = "DELETE FROM approved_bookings WHERE appointment_id = ?";
                $stmt_delete = $conn->prepare($sql_delete_approved);
                $stmt_delete->bind_param("i", $appointment_id);
                $stmt_delete->execute();

                echo "Booking marked as no-show successfully.";
            } else {
                echo "Error inserting record: " . $conn->error;
            }
        } else {
            echo "No approved booking found with the given ID.";
        }
    }

    // Redirect back to the appropriate bookings page (adjust the URL as needed)
    header("Location:../../booking/approved_booking_ui.php");
    exit();
} else {
    echo "Invalid request method.";
}
?>
