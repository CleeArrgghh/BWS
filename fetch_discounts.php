<?php
session_start();
include_once '../bws_ui/db_connection/db_connection.php';

header('Content-Type: application/json');

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch discount data
$query = "
    SELECT 
        s.name AS service_name,
        s.price AS original_price,
        d.discount_percentage,
        (s.price - (s.price * (d.discount_percentage / 100))) AS discounted_price,
        d.start_time,
        d.end_time,
        d.service_id
    FROM discounts AS d
    JOIN services AS s ON d.service_id = s.id
";
$result = $conn->query($query);

$discounts = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $discounts[] = [
            $row['service_name'],
            '₱' . number_format($row['original_price'], 2),
            $row['discount_percentage'] . '%',
            '₱' . number_format($row['discounted_price'], 2),
            $row['start_time'],
            $row['end_time'],
            $row['service_id']  
        ];
    }
}

$conn->close();

// Output as JSON for DataTables
echo json_encode(['data' => $discounts]);
?>
