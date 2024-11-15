<?php
// Start session
session_start();

// Include database connection
include "../../db_connection/db_connection.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect data from the form
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $gender = $_POST['gender'];
    $position_id = $_POST['staffPosition'];
    $phone = $_POST['phone'];
    
    // Process staff image upload
    $image_path = '';
    if (isset($_FILES['staffImage']) && $_FILES['staffImage']['error'] == 0) {
        $uploadDir = '../../staff_images/';
        $imagePath = basename($_FILES['staffImage']['name']);
        $targetFilePath = $uploadDir . $imagePath;
        
        // Move uploaded file
        if (move_uploaded_file($_FILES['staffImage']['tmp_name'], $targetFilePath)) {
            $image_path = $imagePath;
        } else {
            $_SESSION['error'] = "Image upload failed.";
            header("Location: ../manage_staff_positions.php");
            exit();
        }
    }

    // Insert into database
    $sql = "INSERT INTO tbl_staff (firstname, middlename, lastname, dob, address, status, gender, position_id, phone, image_path) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $firstname, $middlename, $lastname, $dob, $address, $status, $gender, $position_id, $phone, $image_path);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Staff member added successfully.";
    } else {
        $_SESSION['error'] = "Failed to add staff member. Please try again.";
    }

    // Redirect to the staff management page
    header("Location: ../staff/manage_staff_positions.php");
    exit();
} else {
    // Redirect to form if not submitted
    header("Location: ../staff/manage_staff_positions.php");
    exit();
}
?>
