

<?php
session_start();
include '../../db_connection/db_connection.php';


// Fetch completed bookings from the database
$sql = "SELECT * FROM tbl_complete_bookings";
$result = $conn->query($sql);

$completedBookings = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $completedBookings[] = $row;
    }
}

// Your Semaphore API key
$apiKey = 'ed49311fb8620f4e674df7cd10181e95';

// Function to send SMS
function sendSms($phone, $message, $apiKey) {
    $url = 'https://api.semaphore.co/api/v4/messages';
    
    // Prepare the data
    $data = [
        'apikey' => $apiKey,
        'number' => $phone,
        'message' => $message,
        'sendername' => 'Bws' 
    ];

    // Use cURL to send the request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute and get the response
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Check if the form has been submitted
if (isset($_POST['action'])) {
    $bookingId = $_POST['booking_id'];
    $action = $_POST['action'];

    // Get the phone number of the user related to the booking
    $sql_user = "SELECT b.id, b.phone, u.username, s.name AS service_name, b.booking_date, b.booking_time 
                 FROM tbl_booking b 
                 JOIN tbl_users u ON b.user_id = u.user_id 
                 JOIN services s ON b.service_id = s.id 
                 WHERE b.id = ?";
    $stmt_user = $conn->prepare($sql_user);
    $stmt_user->bind_param("i", $bookingId);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();

    if ($result_user->num_rows > 0) {
        $bookingData = $result_user->fetch_assoc();
        $phone = $bookingData['phone'];
        $username = $bookingData['username'];
        $serviceName = $bookingData['service_name'];
        $bookingDate = $bookingData['booking_date'];
        $bookingTime = $bookingData['booking_time'];
    }

    // Handle approval or cancellation
    if ($action === 'approve') {
        $sql_action = "UPDATE tbl_booking SET status = 'approved' WHERE id = ?";
        $message = "Salamat, $username! Ang iyong booking para sa $serviceName sa $bookingDate, $bookingTime ay approved na. Salamat sa pag-book sa Bernadette Wellness Spa.";
    } elseif ($action === 'cancel') {
        $sql_action = "UPDATE tbl_booking SET status = 'canceled' WHERE id = ?";
        $message = "Opps, $username. Ang iyong booking para sa $serviceName sa $bookingDate, $bookingTime ay nakansela. Maaring mag-book ulit at siguraduhing walang mali sa pag-fill up. Thank you!";
    }

    // Update booking status in the database
    $stmt = $conn->prepare($sql_action);
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Booking " . ($action === 'approve' ? "approved" : "canceled") . " successfully.";

        // Send the SMS notification using Semaphore
        $smsResponse = sendSms($phone, $message, $apiKey);

        // Optional: You can log or display the SMS response for debugging
        // $_SESSION['sms_response'] = $smsResponse;

    } else {
        $_SESSION['error'] = "Failed to " . ($action === 'approve' ? "approve" : "cancel") . " booking: " . $conn->error;
    }

    // Redirect back to the approval page
    header("Location: ../admin_approve_bookings.php");
    exit();
}




