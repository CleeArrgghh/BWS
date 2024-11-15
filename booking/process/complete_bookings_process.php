<?php
// Include the database connection file
include('../../db_connection/db_connection.php');

// Check if the appointment ID is provided
if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);

    // Start a transaction
    if ($conn->begin_transaction() === false) {
        header("Location: ../approved_booking_ui.php?error=" . urlencode("Failed to start transaction."));
        exit();
    }

    try {
        // Fetch the approved booking details
        $sql_fetch = "SELECT * FROM approved_bookings WHERE appointment_id = ?";
        $stmt_fetch = $conn->prepare($sql_fetch);
        if ($stmt_fetch === false) {
            throw new Exception("Failed to prepare statement for fetching booking.");
        }
        $stmt_fetch->bind_param("i", $appointment_id);
        $stmt_fetch->execute();
        $result = $stmt_fetch->get_result();

        if ($result->num_rows > 0) {
            // Get booking data
            $booking = $result->fetch_assoc();

            // Insert the booking into complete_bookings table
            $sql_insert = "INSERT INTO complete_bookings (appointment_id, full_name, service_names, appointment_date, appointment_time, payment_method, total_price) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            if ($stmt_insert === false) {
                throw new Exception("Failed to prepare statement for inserting completed booking. Error: " . $conn->error);
            }
            $stmt_insert->bind_param(
                "issssdi",
                $booking['appointment_id'],
                $booking['full_name'],
                $booking['service_names'],
                $booking['appointment_date'],
                $booking['appointment_time'],
                $booking['payment_method'],
                $booking['total_price']
            );
            $stmt_insert->execute();

            // Delete the booking from approved_bookings table
            $sql_delete = "DELETE FROM approved_bookings WHERE appointment_id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
            if ($stmt_delete === false) {
                throw new Exception("Failed to prepare statement for deleting approved booking.");
            }
            $stmt_delete->bind_param("i", $appointment_id);
            $stmt_delete->execute();

            // Commit the transaction
            $conn->commit();

            // Redirect with a success message
            header("Location: ../approved_booking_ui.php?message=Booking completed successfully");
            exit();
        } else {
            throw new Exception("Booking not found.");
        }
    } catch (Exception $e) {
        // Rollback the transaction in case of error
        $conn->rollback();

        // Redirect with an error message
        header("Location: ../approved_booking_ui.php?error=" . urlencode($e->getMessage()));
        exit();
    }
} else {
    // Redirect if no appointment ID is provided
    header("Location: ../approved_booking_ui.php?error=Invalid appointment ID");
    exit();
}
?>
