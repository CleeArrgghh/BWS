<?php
include_once '../bws_ui/db_connection/db_connection.php';

$serviceName = mysqli_real_escape_string($conn, $_POST['serviceName']);
$serviceDescription = mysqli_real_escape_string($conn, $_POST['serviceDescription']);
$serviceCategory = mysqli_real_escape_string($conn, $_POST['serviceCategory']);
$servicePrice = mysqli_real_escape_string($conn, $_POST['servicePrice']);

// Handle file upload
$serviceImage = '';
if (isset($_FILES['serviceImage']) && $_FILES['serviceImage']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../bws_ui/services_images/';
    $uploadFile = $uploadDir . basename($_FILES['serviceImage']['name']);
    if (move_uploaded_file($_FILES['serviceImage']['tmp_name'], $uploadFile)) {
        $serviceImage = basename($_FILES['serviceImage']['name']);
    }
}

// Check if the service already exists
$checkQuery = "SELECT id FROM services WHERE name='$serviceName'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // Service already exists
    mysqli_close($conn);
    header("Location: add_services.php?exists=true&name=" . urlencode($serviceName));
    exit();
}

// Insert new service
$query = "INSERT INTO services (name, description, category_id, price, image) VALUES ('$serviceName', '$serviceDescription', '$serviceCategory', '$servicePrice', '$serviceImage')";

if (mysqli_query($conn, $query)) {
    $success = true;
} else {
    $success = false;
}

mysqli_close($conn);

// Redirect with success status
header("Location: add_services.php?success=" . ($success ? "true" : "false") . "&name=" . urlencode($serviceName) . "&description=" . urlencode($serviceDescription) . "&category=" . urlencode($serviceCategory) . "&price=" . urlencode($servicePrice) . "&image=" . urlencode($serviceImage));
exit();
?>
