<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start();
include '../../db_connection/db_connection.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user_id is set in session
    if (!isset($_SESSION['user_id'])) {
        echo "<script>Swal.fire({ icon: 'error', title: 'Error', text: 'User not logged in.' }).then(() => { window.location.href = '../../bws_ui/login.php'; });</script>";
        exit;
    }  

    // Get the form data
    $user_id = $_SESSION['user_id']; 
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $payment_method = $_POST['payment_method'];
    $total_price = $_POST['total_price'];
    $service_ids = $_POST['service_ids']; // Comma-separated service IDs

    // Validate the form data
    if (empty($user_id) || empty($appointment_date) || empty($appointment_time) || empty($payment_method) || empty($total_price) || empty($service_ids)) {
        echo "<script>Swal.fire({ icon: 'error', title: 'Error', text: 'All fields are required.' }).then(() => { window.location.href = '../../bws_ui/booking/form.php'; });</script>";
        exit;
    }

    // Insert appointment into the appointments table
    $sql = "INSERT INTO appointments (user_id, appointment_date, appointment_time, payment_method, total_price, status) VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isssd', $user_id, $appointment_date, $appointment_time, $payment_method, $total_price);

    if ($stmt->execute()) {
        $appointment_id = $stmt->insert_id;

        // Insert booked services into the booked_services table
        $service_ids_array = explode(',', $service_ids);
        $sql_service = "INSERT INTO booked_services (appointment_id, service_id, price) VALUES (?, ?, ?)";
        $stmt_service = $conn->prepare($sql_service);

        foreach ($service_ids_array as $service_id) {
            // Get the service price from the services table
            $sql_price = "SELECT price FROM services WHERE id = ?";
            $stmt_price = $conn->prepare($sql_price);
            $stmt_price->bind_param('i', $service_id);
            $stmt_price->execute();
            $stmt_price->bind_result($service_price);
            $stmt_price->fetch();
            $stmt_price->close();

            // Insert into booked_services table
            $stmt_service->bind_param('iid', $appointment_id, $service_id, $service_price);
            $stmt_service->execute();
        }

        // Display success message with SweetAlert and redirect
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Appointment booked successfully!',
                    text: 'Please wait for approving your appointment.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = '../../booking/booking.php';
                });
              </script>";
    } else {
        echo "<script>Swal.fire({ icon: 'error', title: 'Error', text: '" . $stmt->error . "' }).then(() => { window.location.href = '../../bws_ui/booking/form.php'; });</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<script>Swal.fire({ icon: 'error', title: 'Invalid request', text: 'Invalid request.' }).then(() => { window.location.href = '../../bws_ui/booking/form.php'; });</script>";
}


?>
</body>
</html>
