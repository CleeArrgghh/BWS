
admin Secrete key: 
5AwQmZFoCWDHcj-KR0lFJ6cL7RSXd-VsMh03IKQK6-4


semaphore Api:
ed49311fb8620f4e674df7cd10181e95

recapcha api client side:
6LelZVIqAAAAAD0O_gpXUuHKgyFJzyS2Prdh-_Vf

recapcha serverside:
6LelZVIqAAAAAA2kWlGngEL9kIHjuS2j7wVsuYEw





CREATE TABLE IF NOT EXISTS tbl_booking (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    category_id INT NOT NULL,  -- Reference to the service category if needed
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tbl_users(user_id),
    FOREIGN KEY (service_id) REFERENCES services(id),
    FOREIGN KEY (category_id) REFERENCES service_categories(id)
);







<?php
session_start();
include '../../db_connection/db_connection.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection settings
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bwsdb';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if booking ID is set
if (!isset($_POST['booking_id'])) {
    die("Booking ID is required");
}

$booking_id = $_POST['booking_id'];

// Step 1: Fetch booking details from `user_bookings`
$sql = "SELECT * FROM user_bookings WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

if (!$booking) {
    die("Booking not found");
}

// Step 2: Update the status in `user_bookings` table
$update_sql = "UPDATE user_bookings SET status = 'approved' WHERE booking_id = ?";
$update_stmt = $conn->prepare($update_sql);
if (!$update_stmt) {
    die("Prepare failed for update: " . $conn->error);
}
$update_stmt->bind_param("i", $booking_id);
$update_stmt->execute();

// Step 3: Insert the approved booking into the `approved_bookings` table with status set to 'approved'
$insert_sql = "
    INSERT INTO approved_bookings 
    (booking_id, user_id, appointment_date, appointment_time, total_price, payment_method, status, created_at)
    VALUES (?, ?, ?, ?, ?, ?, 'approved', ?)";
$insert_stmt = $conn->prepare($insert_sql);
if (!$insert_stmt) {
    die("Prepare failed for insert: " . $conn->error);
}

$insert_stmt->bind_param(
    "iissdss", 
    $booking['booking_id'], 
    $booking['user_id'], 
    $booking['appointment_date'], 
    $booking['appointment_time'], 
    $booking['total_price'], 
    $booking['payment_method'], 
    $booking['created_at']
);

if ($insert_stmt->execute()) {
    // Retrieve the new approved booking ID from `approved_bookings`
    $approved_booking_id = $insert_stmt->insert_id;

    // Step 4: Insert each selected service into `approved_booking_services` using `approved_booking_id`
    $service_sql = "INSERT INTO approved_booking_services (approved_booking_id, service_id) VALUES (?, ?)";
    $service_stmt = $conn->prepare($service_sql);
    if (!$service_stmt) {
        die("Prepare failed for service insert: " . $conn->error);
    }

    // Retrieve services linked to the original booking
    $services_query = "SELECT service_id FROM user_booking_services WHERE booking_id = ?";
    $services_stmt = $conn->prepare($services_query);
    $services_stmt->bind_param("i", $booking_id);
    $services_stmt->execute();
    $services_result = $services_stmt->get_result();

    // Insert each service associated with the approved booking
    while ($service_row = $services_result->fetch_assoc()) {
        $service_id = $service_row['service_id'];
        $service_stmt->bind_param("ii", $approved_booking_id, $service_id);  // Use approved_booking_id here
        if (!$service_stmt->execute()) {
            die("Error inserting service: " . $service_stmt->error);
        }
    }

    // Step 5: Delete related services from `user_booking_services`
    $delete_services_sql = "DELETE FROM user_booking_services WHERE booking_id = ?";
    $delete_services_stmt = $conn->prepare($delete_services_sql);
    $delete_services_stmt->bind_param("i", $booking_id);
    if (!$delete_services_stmt->execute()) {
        die("Error deleting services from user_booking_services: " . $delete_services_stmt->error);
    }

    // Step 6: Delete the original booking from `user_bookings`
    $delete_booking_sql = "DELETE FROM user_bookings WHERE booking_id = ?";
    $delete_booking_stmt = $conn->prepare($delete_booking_sql);
    $delete_booking_stmt->bind_param("i", $booking_id);
    if (!$delete_booking_stmt->execute()) {
        die("Error deleting booking from user_bookings: " . $delete_booking_stmt->error);
    }

    // Success message and redirection
    $_SESSION['message'] = "Booking approved successfully!";
    header("Location: ../pending_booking.php");
} else {  
    echo "Error: " . $insert_stmt->error;
}

// Close remaining statements
$stmt->close();
$update_stmt->close();
$insert_stmt->close();
$conn->close();
?>
