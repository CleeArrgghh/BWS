<?php
// cancel_booking_process.php

// Include database connection
require_once '../../db_connection/db_connection.php';

// Semaphore API key
$apiKey = "ed49311fb8620f4e674df7cd10181e95";

// Define log file for errors
$logFile = '../../logs/error_log.txt';

if (isset($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    
    // Fetch the current status of the booking
    $query = "SELECT a.*, CONCAT(u.firstname, ' ', u.lastname) AS full_name, u.phone AS user_phone, GROUP_CONCAT(s.name SEPARATOR ', ') AS service_names 
              FROM appointments a
              JOIN tbl_users u ON a.user_id = u.user_id
              JOIN booked_services bs ON a.id = bs.appointment_id
              JOIN services s ON bs.service_id = s.id
              WHERE a.id = ?
              GROUP BY a.id";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Check if the appointment is not already cancelled
        if ($row['status'] !== 'cancelled') {
            // Update the status to 'cancelled'
            $update_query = "UPDATE appointments SET status = 'cancelled' WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param('i', $appointment_id);

            if ($update_stmt->execute()) {
                // Insert the cancelled booking into the cancelled_bookings table
                $insert_query = "INSERT INTO cancelled_bookings (appointment_id, full_name, service_names, appointment_date, appointment_time, payment_method, total_price, cancelled_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $insert_stmt = $conn->prepare($insert_query);
                $insert_stmt->bind_param('isssssd', $row['id'], $row['full_name'], $row['service_names'], $row['appointment_date'], $row['appointment_time'], $row['payment_method'], $row['total_price']);

                if (!$insert_stmt->execute()) {
                    // Log error if insertion fails
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error inserting into cancelled_bookings: " . $insert_stmt->error . PHP_EOL, FILE_APPEND);
                    echo "<div id='alert'></div>";
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                    echo "<script>Swal.fire({icon: 'error', title: 'Failed', text: 'Failed to insert into cancelled bookings'}).then(() => { window.location.href = '../../booking/approved_booking_ui.php'; });</script>";
                    exit;
                }

                // Remove the cancelled booking from the approved_bookings table
                $delete_query = "DELETE FROM approved_bookings WHERE appointment_id = ?";
                $delete_stmt = $conn->prepare($delete_query);
                $delete_stmt->bind_param('i', $appointment_id);

                if (!$delete_stmt->execute()) {
                    // Log error if deletion fails
                    file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error deleting from approved_bookings: " . $delete_stmt->error . PHP_EOL, FILE_APPEND);
                    echo "<div id='alert'></div>";
                    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                    echo "<script>Swal.fire({icon: 'error', title: 'Failed', text: 'Failed to delete from approved bookings'}).then(() => { window.location.href = '../../booking/approved_booking_ui.php'; });</script>";
                    exit;
                }

                // Prepare SMS message
                $userPhone = $row['user_phone'];
                $fullName = $row['full_name'];
                $serviceNames = $row['service_names'];
                $appointmentDate = $row['appointment_date'];
                $appointmentTime = $row['appointment_time'];
                $message = "Hello $fullName, we regret to inform you that your appointment for $serviceNames on $appointmentDate at $appointmentTime has been canceled.";

                // Send SMS using Semaphore API
                $ch = curl_init();
                $smsParameters = array(
                    'apikey' => $apiKey,
                    'number' => $userPhone,
                    'message' => $message,
                    'sendername' => 'Bws' // Replace with your desired sender name
                );
                curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($smsParameters));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Execute cURL and get response
                $output = curl_exec($ch);
                curl_close($ch);

                // Optional: Log or handle the response from Semaphore

                // If update successful, show success alert
                echo "<div id='alert'></div>";
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>Swal.fire({icon: 'success', title: 'Cancelled', text: 'Booking cancelled successfully'}).then(() => { window.location.href = '../../booking/approved_booking_ui.php'; });</script>";
                exit;
            } else {
                // Log error if update fails
                file_put_contents($logFile, date('Y-m-d H:i:s') . " - Error updating appointment status: " . $update_stmt->error . PHP_EOL, FILE_APPEND);
                echo "<div id='alert'></div>";
                echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
                echo "<script>Swal.fire({icon: 'error', title: 'Failed', text: 'Failed to cancel booking'}).then(() => { window.location.href = '../../booking/approved_booking_ui.php'; });</script>";
                exit;
            }
        } else {
            // If already cancelled, show alert
            echo "<div id='alert'></div>";
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>Swal.fire({icon: 'info', title: 'Already Cancelled', text: 'Booking is already cancelled'}).then(() => { window.location.href = '../../booking/approved_booking_ui.php'; });</script>";
            exit;
        }
    } else {
        // If no such booking found, show alert
        echo "<div id='alert'></div>";
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>Swal.fire({icon: 'error', title: 'Not Found', text: 'Booking not found'}).then(() => { window.location.href = '../../booking/cancelled_booking_ui.php'; });</script>";
        exit;
    }
} else {
    // If appointment ID not provided, show alert
    echo "<div id='alert'></div>";
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>Swal.fire({icon: 'error', title: 'Invalid Request', text: 'Invalid request'}).then(() => { window.location.href = '../../booking/cancelled_booking_ui.php'; });</script>";
    exit;
}
?>
