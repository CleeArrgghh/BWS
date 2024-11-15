<?php
include_once '../bws_ui/db_connection/db_connection.php';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the posted data
    $serviceId = $_POST['serviceId'];
    $serviceName = $_POST['serviceName'];
    $serviceDescription = $_POST['serviceDescription'];
    $servicePrice = $_POST['servicePrice'];
    $serviceCategory = $_POST['serviceCategory'];
    $imageUpdated = false;
    
    // Check if an image was uploaded
    if (!empty($_FILES['serviceImage']['name'])) {
        $targetDir = "../bws_ui/services_images/";
        $targetFile = $targetDir . basename($_FILES["serviceImage"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $newFileName = $targetDir . uniqid() . '.' . $imageFileType;

        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["serviceImage"]["tmp_name"], $newFileName)) {
            $imageUpdated = true;
        } else {
            echo "Sorry, there was an error uploading the file.";
            exit;
        }
    }

    // Prepare the SQL query
    $query = "UPDATE services SET name = ?, description = ?, price = ?, category_id = ?";
    if ($imageUpdated) {
        $query .= ", image = ?";
    }
    $query .= " WHERE id = ?";

    $stmt = $conn->prepare($query);
    
    // Bind parameters
    if ($imageUpdated) {
        $stmt->bind_param("ssdisi", $serviceName, $serviceDescription, $servicePrice, $serviceCategory, $newFileName, $serviceId);
    } else {
        $stmt->bind_param("ssdis", $serviceName, $serviceDescription, $servicePrice, $serviceCategory, $serviceId);
    }

    // Execute the query
    if ($stmt->execute()) {
        // Redirect with success message
        header("Location: add_services.php?status=success");
        exit;
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}

$conn->close();
?>
