<?php
// Start the session and include the database connection
session_start();
include "../../db_connection/db_connection.php";

// Check if the form is submitted and the staff ID is provided
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['staff_id'])) {
    // Retrieve the form data
    $staff_id = $_POST['staff_id'];
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $position_id = $_POST['staffPosition'];
    $phone = $_POST['phone'];
    
    // Initialize the image path
    $image_path = null;

    // Check if a new image file is uploaded
    if (isset($_FILES['staffImage']) && $_FILES['staffImage']['error'] == 0) {
        $targetDir = "../../staff_images/";
        $fileName = basename($_FILES["staffImage"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileType, $allowedTypes)) {
            // Move the uploaded file to the target directory
            if (move_uploaded_file($_FILES["staffImage"]["tmp_name"], $targetFilePath)) {
                $image_path = $fileName;
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        } else {
            echo "Only JPG, JPEG, PNG, & GIF files are allowed.";
            exit;
        }
    }

// Add this line to retrieve the status from the form
$status = $_POST['status'];

// Prepare the SQL update query
$sql = "UPDATE tbl_staff SET 
            firstname = ?, 
            middlename = ?, 
            lastname = ?, 
            dob = ?, 
            address = ?, 
            gender = ?, 
            position_id = ?, 
            phone = ?, 
            status = ?"; // Add status here

// Append image path update if a new image is uploaded
if ($image_path) {
    $sql .= ", image_path = ?";
}

// Complete the query
$sql .= " WHERE staff_id = ?";

// Initialize the prepared statement
$stmt = $conn->prepare($sql);

// Bind parameters based on whether an image path is provided
if ($image_path) {
    $stmt->bind_param("ssssssisssi", $firstname, $middlename, $lastname, $dob, $address, $gender, $position_id, $phone, $status, $image_path, $staff_id);
} else {
    $stmt->bind_param("ssssssissi", $firstname, $middlename, $lastname, $dob, $address, $gender, $position_id, $phone, $status, $staff_id);
}

// Execute the statement and check if the update was successful
if ($stmt->execute()) {
    // Redirect back to the staff management page with a success message
    $_SESSION['message'] = "Staff details updated successfully!";
    header("Location: ../manage_staff.php");
    exit;
} else {
    echo "Error updating record: " . $conn->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

} else {
    // Redirect if the form was not submitted correctly
    header("Location: ../manage_staff.php");
    exit;
}
?>
