<?php
session_start();
include '../../bws_ui/db_connection/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Initialize arrays for each booking status
$pendingBookings = [];
$completedBookings = [];
$noShowBookings = [];
$canceledBookings = [];

$userId = $_SESSION['user_id']; // Adjust based on your session structure

// Fetch bookings for the user
$query = "SELECT a.id, a.appointment_date, a.appointment_time, a.payment_method, a.total_price, 
                 b.service_name, a.status 
          FROM appointments a 
          JOIN booked_services b ON a.id = b.appointment_id 
          WHERE a.user_id = ? 
          ORDER BY a.appointment_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $booking = [
        'date' => $row['appointment_date'],
        'time' => $row['appointment_time'],
        'service' => $row['service_name'],
        'price' => $row['total_price'],
        'payment' => $row['payment_method']
    ];

    switch ($row['status']) {
        case 'pending':
            $pendingBookings[] = $booking;
            break;
        case 'completed':
            $completedBookings[] = $booking;
            break;
        case 'no-show':
            $noShowBookings[] = $booking;
            break;
        case 'canceled':
            $canceledBookings[] = $booking;
            break;
    }
}

// Return as JSON
echo json_encode([
    'pending' => $pendingBookings,
    'completed' => $completedBookings,
    'no_show' => $noShowBookings,
    'canceled' => $canceledBookings
]);
?>
