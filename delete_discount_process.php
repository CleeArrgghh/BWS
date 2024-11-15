<?php
session_start();
include_once '../bws_ui/db_connection/db_connection.php';

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Get service ID from the AJAX request
$service_id = isset($_POST['service_id']) ? $_POST['service_id'] : null;

if (!$service_id) {
    echo json_encode(['status' => 'error', 'message' => 'Service ID is required.']);
    exit;
}

// Prepare and execute the delete query
$query = "DELETE FROM discounts WHERE service_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit;
}

$stmt->bind_param("i", $service_id);
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Discount deleted successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No discount found for the specified service ID.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete discount: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
