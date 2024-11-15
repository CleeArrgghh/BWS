<?php
session_start();
include_once '../../db_connection/db_connection.php';

$response = array('status' => 'error', 'message' => 'Unable to update appointment.');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $new_date = $_POST['appointment_date'];
    $new_time = $_POST['appointment_time'];

    // Prepare the update query
    $query = "UPDATE appointments SET appointment_date = ?, appointment_time = ? WHERE id = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        $response['message'] = 'Failed to prepare statement: ' . $conn->error;
    } else {
        $stmt->bind_param("ssi", $new_date, $new_time, $appointment_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $response = array('status' => 'success', 'message' => 'Appointment updated successfully.');
                $_SESSION['success_message'] = 'Appointment date and time updated successfully.';
            } else {
                $response['message'] = 'No changes made to the appointment. The date/time may be the same.';
                $_SESSION['error_message'] = $response['message'];
            }
        } else {
            $response['message'] = 'Failed to update appointment: ' . $stmt->error;
            $_SESSION['error_message'] = $response['message'];
        }

        $stmt->close();
    }

    $conn->close();
}

// Send JSON response for AJAX or redirect with session messages
if (isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1') {
    echo json_encode($response);
} else {
    header("Location: ../approved_booking_ui.php");
    exit();
}
?>
