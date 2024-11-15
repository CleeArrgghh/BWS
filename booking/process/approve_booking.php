<?php
// Include your database connection
include '../../db_connection/db_connection.php';

// Semaphore API key
$apiKey = "ed49311fb8620f4e674df7cd10181e95";

// Check if the appointment ID is passed in the URL
if (isset($_GET['id'])) {
    $appointmentId = $_GET['id'];

    // Update the appointment status to 'approved'
    $sql = "UPDATE appointments SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointmentId);

    if ($stmt->execute()) {
        // Now insert the approved booking into the approved_bookings table
        $fetchSql = "SELECT a.id AS appointment_id, a.user_id, CONCAT(u.firstname, ' ', u.lastname) AS full_name, u.phone AS user_phone, a.appointment_date, a.appointment_time, a.payment_method, a.total_price, GROUP_CONCAT(s.name SEPARATOR ', ') AS service_names 
                     FROM appointments a
                     JOIN tbl_users u ON a.user_id = u.user_id
                     JOIN booked_services bs ON a.id = bs.appointment_id
                     JOIN services s ON bs.service_id = s.id
                     WHERE a.id = ?";
        $fetchStmt = $conn->prepare($fetchSql);
        $fetchStmt->bind_param("i", $appointmentId);
        $fetchStmt->execute();
        $result = $fetchStmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            // Insert into approved_bookings table
            $insertSql = "INSERT INTO approved_bookings (appointment_id, user_id, full_name, service_names, appointment_date, appointment_time, payment_method, total_price)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("iisssssd", 
                $row['appointment_id'], 
                $row['user_id'], 
                $row['full_name'], 
                $row['service_names'], 
                $row['appointment_date'], 
                $row['appointment_time'], 
                $row['payment_method'], 
                $row['total_price']
            );
            $insertStmt->execute();

            // Prepare SMS message
            $userPhone = $row['user_phone'];
            $fullName = $row['full_name'];
            $serviceNames = $row['service_names'];
            $appointmentDate = $row['appointment_date'];
            $appointmentTime = $row['appointment_time'];
            $message = "Hello $fullName, your appointment for $serviceNames on $appointmentDate at $appointmentTime has been approved.";

            // Send SMS using Semaphore API
            $ch = curl_init();
            $smsParameters = array(
                'apikey' => $apiKey,
                'number' => $userPhone,
                'message' => $message,
                'sendername' => 'Bws' // Replace with your desired sender name
            );
            curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($smsParameters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Execute cURL and get response
            $output = curl_exec($ch);
            curl_close($ch);

            // Optional: Log or handle the response from Semaphore
        }

        // Redirect with a success message
        header("Location: ../../booking/pending_booking.php?message=success");
        exit();   
    } else {
        // If update fails, redirect with an error message
        header("Location: ../../booking/pending_booking.php?message=error");
        exit(); 
    }

    // Close the statements
    $stmt->close();
    $fetchStmt->close();
    if (isset($insertStmt)) {
        $insertStmt->close();
    }
} else {
    // Redirect back if the appointment ID is not set
    header("Location: ../../booking/pending_booking.php?message=invalid");
    exit();   
}

// Close the database connection
$conn->close();  
?>
