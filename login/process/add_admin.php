<?php
include '../../db_connection/db_connection.php'; // Include your database connection script

// Admin credentials
$username = 'admin123'; // Replace with your admin username
$password = 'admin456'; // Replace with your admin password
$secret_key = '0c1ff2c4a550923caef0a640ab04c2ce'; // Replace with your secret key

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute the SQL statement to insert the admin
$stmt = $conn->prepare("INSERT INTO tbl_admin (username, password, secret_key) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $secret_key);

if ($stmt->execute()) {
    echo "Admin user added successfully.";
} else {
    echo "Error adding admin user: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
