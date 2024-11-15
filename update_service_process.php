<?php
include_once '../bws_ui/db_connection/db_connection.php';

// Check if we are updating or adding a new service
$serviceId = $_POST['serviceId'];
$serviceName = $_POST['serviceName'];
$serviceDescription = $_POST['serviceDescription'];
$serviceCategory = $_POST['serviceCategory'];
$servicePrice = $_POST['servicePrice'];
$serviceImage = isset($_FILES['serviceImage']['name']) ? $_FILES['serviceImage']['name'] : '';

$title = '';
$text = '';
$icon = '';

// Check if the service name already exists
$checkQuery = "SELECT * FROM services WHERE name='$serviceName' AND id != '$serviceId'";
$checkResult = mysqli_query($conn, $checkQuery);

if (mysqli_num_rows($checkResult) > 0) {
    // Service with the same name already exists
    $title = 'Error!';
    $text = 'Service with this name already exists.';
    $icon = 'error';
} else {
    if ($serviceId) {
        // Fetch the current image from the database to delete it if necessary
        $selectQuery = "SELECT image FROM services WHERE id='$serviceId'";
        $result = mysqli_query($conn, $selectQuery);

        if ($result && $row = mysqli_fetch_assoc($result)) {
            $oldImage = $row['image'];
        }

        // Updating an existing service
        $query = "UPDATE services SET name='$serviceName', description='$serviceDescription', category_id='$serviceCategory', price='$servicePrice'";

        // Check if the user uploaded a new image
        if ($serviceImage) {
            // Define the new image's path
            $targetDir = "../bws_ui/services_images/";
            $targetFile = $targetDir . basename($serviceImage);

            // Upload the new image
            if (move_uploaded_file($_FILES["serviceImage"]["tmp_name"], $targetFile)) {
                // Delete the old image from the folder if it exists
                $oldImagePath = $targetDir . $oldImage;
                if (!empty($oldImage) && file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Delete the old image
                }

                // Append the new image to the update query
                $query .= ", image='$serviceImage'";
            } else {
                $title = 'Error!';
                $text = 'Failed to upload the new image.';
                $icon = 'error';
            }
        }

        $query .= " WHERE id='$serviceId'";
    } else {
        // Adding a new service
        $targetDir = "../bws_ui/services_images/";
        $targetFile = $targetDir . basename($serviceImage);

        if (move_uploaded_file($_FILES["serviceImage"]["tmp_name"], $targetFile)) {
            $query = "INSERT INTO services (name, description, category_id, price, image) VALUES ('$serviceName', '$serviceDescription', '$serviceCategory', '$servicePrice', '$serviceImage')";
        } else {
            $title = 'Error!';
            $text = 'Failed to upload the image.';
            $icon = 'error';
        }
    }

    // Execute the query and handle results
    if (empty($title) && mysqli_query($conn, $query)) {
        $title = 'Success!';
        $text = 'Service has been ' . ($serviceId ? 'updated' : 'added') . ' successfully!';
        $icon = 'success';
    } else {
        if (empty($title)) {
            $title = 'Error!';
            $text = 'Failed to save the service data.';
            $icon = 'error';
        }
    }
}

// Close the connection if open
if ($conn instanceof mysqli && !$conn->connect_errno) {
    mysqli_close($conn); // Close the connection
}

// Output SweetAlert2 and redirection script
echo "<!DOCTYPE html>
<html>
<head>
    <title>Process Service</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        Swal.fire({
            title: '{$title}',
            text: '{$text}',
            icon: '{$icon}',
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = 'add_services.php';
        });
    </script>
</body>
</html>";
