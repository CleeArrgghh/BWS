<?php
include_once '../bws_ui/db_connection/db_connection.php';

// Initialize response variables
$title = '';
$text = '';
$icon = '';

// Check if the ID is provided and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $serviceId = intval($_GET['id']);

    // Retrieve the image filename associated with the service
    $selectQuery = "SELECT image FROM services WHERE id = ?";
    if ($stmt = $conn->prepare($selectQuery)) {
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $stmt->bind_result($image);
        $stmt->fetch();
        $stmt->close();
        
        // Check if the image exists in the folder
        $imagePath = "../bws_ui/services_images/" . $image;
        if (!empty($image) && file_exists($imagePath)) {
            // Delete the image file
            if (unlink($imagePath)) {
                // Image deleted successfully
                $imageDeleted = true;
            } else {
                // Failed to delete the image
                $imageDeleted = false;
            }
        } else {
            // No image found
            $imageDeleted = true; // Even if no image, we can proceed with service deletion
        }

        // Now prepare the delete query for the service
        if ($imageDeleted) {
            $deleteQuery = "DELETE FROM services WHERE id = ?";
            if ($stmt = $conn->prepare($deleteQuery)) {
                $stmt->bind_param("i", $serviceId);

                if ($stmt->execute()) {
                    $title = 'Success!';
                    $text = 'Service and associated image deleted successfully!';
                    $icon = 'success';
                } else {
                    $title = 'Error!';
                    $text = 'Failed to delete service.';
                    $icon = 'error';
                }

                $stmt->close(); // Close the statement
            } else {
                $title = 'Error!';
                $text = 'Service deletion query preparation failed.';
                $icon = 'error';
            }
        } else {
            $title = 'Error!';
            $text = 'Failed to delete the associated image.';
            $icon = 'error';
        }
    } else {
        $title = 'Error!';
        $text = 'Failed to retrieve service information.';
        $icon = 'error';
    }
} else {
    $title = 'Error!';
    $text = 'Invalid service ID.';
    $icon = 'error';
}

// Check if the connection is open before closing it
if ($conn instanceof mysqli && !$conn->connect_errno) {
    mysqli_close($conn); // Close the connection
}

// Output SweetAlert2 and redirection script
echo "<!DOCTYPE html>
<html>
<head>
    <title>Delete Service</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        Swal.fire({
            title: '{$title}',
            text: '{$text}',
            icon: '{$icon}',
            timer: 1500,
            showConfirmButton: false
        }).then(() => {
            window.location.href = 'add_services.php';
        });
    </script>
</body>
</html>";
?>
