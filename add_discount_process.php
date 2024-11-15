<?php
include_once '../bws_ui/db_connection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $discount_percentage = isset($_POST['discount_percentage']) ? intval($_POST['discount_percentage']) : 0;
    $start_time = isset($_POST['start_time']) ? $conn->real_escape_string($_POST['start_time']) : '';
    $end_time = isset($_POST['end_time']) ? $conn->real_escape_string($_POST['end_time']) : '';

    // Validate inputs
    if ($service_id == 0) {
        echo json_encode(["status" => "error", "message" => "Invalid service ID."]);
        exit;
    }
    if ($discount_percentage == 0) {
        echo json_encode(["status" => "error", "message" => "Invalid discount percentage."]);
        exit;
    }
    if (empty($start_time)) {
        echo json_encode(["status" => "error", "message" => "Start time is required."]);
        exit;
    }
    if (empty($end_time)) {
        echo json_encode(["status" => "error", "message" => "End time is required."]);
        exit;
    }

    // Fetch the original price of the service
    $price_sql = "SELECT price FROM services WHERE id = ?";
    $price_stmt = $conn->prepare($price_sql);
    $price_stmt->bind_param("i", $service_id);
    $price_stmt->execute();
    $price_result = $price_stmt->get_result();

    if ($price_result->num_rows == 0) {
        echo json_encode(["status" => "error", "message" => "Service not found."]);
        exit;
    }

    $service = $price_result->fetch_assoc();
    $original_price = $service['price'];
    $discounted_price = $original_price * (1 - $discount_percentage / 100);

    // Check for overlapping discounts
    $check_sql = "SELECT * FROM discounts 
                  WHERE service_id = ? 
                  AND ((start_time <= ? AND end_time >= ?) 
                  OR (start_time <= ? AND end_time >= ?))";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("issss", $service_id, $start_time, $start_time, $end_time, $end_time);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "A discount already exists for this service within the selected time frame."]);
        exit;
    }

    // Insert discount with calculated discounted price
    $sql = "INSERT INTO discounts (service_id, discount_percentage, discounted_price, start_time, end_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iidss", $service_id, $discount_percentage, $discounted_price, $start_time, $end_time);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Discount added successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to add discount."]);
    }

    $stmt->close();
    $check_stmt->close();
    $price_stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
