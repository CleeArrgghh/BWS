<?php
session_start();
include_once '../bws_ui/db_connection/db_connection.php';

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get POST data
$service_id = $_POST['service_id'];
$discount_percentage = $_POST['discount_percentage'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];

// Fetch the original price from the `services` table for calculating the discounted price
$query = "SELECT price FROM services WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $service_id);
$stmt->execute();
$stmt->bind_result($original_price);
$stmt->fetch();
$stmt->close();

if (!$original_price) {
    echo json_encode(['status' => 'error', 'message' => 'Original price not found for service ID.']);
    exit;
}

// Calculate discounted price
$discounted_price = $original_price - ($original_price * ($discount_percentage / 100));

// Prepare and execute the update query
$query = "UPDATE discounts SET discount_percentage = ?, discounted_price = ?, start_time = ?, end_time = ? WHERE service_id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit;
}

$stmt->bind_param("idssi", $discount_percentage, $discounted_price, $start_time, $end_time, $service_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Discount updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No rows updated. Verify submitted data.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update discount: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
