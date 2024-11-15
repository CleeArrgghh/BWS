<?php
// Start the session
session_start();

// Database connection
$host = 'localhost';  // Update as needed
$dbname = 'bwsdb';  // Update with your database name
$username = 'root';  // Update with your database username
$password = '';  // Update with your database password

$conn = new mysqli($host, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch ongoing bookings (status 'pending' or 'approved')
$sql = "SELECT u.firstname, u.lastname, b.appointment_date, b.appointment_time, b.total_price, 
        s.name AS service_name, b.status, b.payment_method
        FROM tbl_booking AS b
        JOIN tbl_users AS u ON b.user_id = u.user_id
        JOIN tbl_services AS s ON b.service_id = s.id
        WHERE b.status IN ('pending', 'approved')";

$result = $conn->query($sql);
$bookings = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['full_name'] = $row['firstname'] . ' ' . $row['lastname'];
        $bookings[] = $row;
    }
}

// Close connection
$conn->close();
