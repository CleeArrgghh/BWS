<?php
include_once '../bws_ui/db_connection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize inputs
    $service_id = mysqli_real_escape_string($conn, $_POST['service_id']);
    $service_name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $date_added = mysqli_real_escape_string($conn, $_POST['date_added']);

    // Handle file upload
    if (!empty($_FILES['service_image']['name'])) {
        $image = $_FILES['service_image']['name'];
        $target_dir = "../bws_ui/services_images/";
        $target_file = $target_dir . basename($image);
        $uploadOk = 1;

        // Check if image file is an actual image
        $check = getimagesize($_FILES['service_image']['tmp_name']);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES['service_image']['size'] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES['service_image']['tmp_name'], $target_file)) {
                echo "The file ". htmlspecialchars(basename($_FILES['service_image']['name'])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        // If no image is uploaded, keep the existing image
        $image = $_POST['existing_image'];
    }

    // Update the service in the database
    $query = "UPDATE services SET name='$service_name', description='$description', price='$price', category_id='$category_id', image='$image', date_added='$date_added' WHERE id='$service_id'";
    if (mysqli_query($conn, $query)) {
        // Redirect with success message
        echo '<script>
                Swal.fire({
                    icon: "success",
                    title: "Service Updated",
                    text: "The service has been successfully updated.",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "services.php";
                    }
                });
              </script>';
    } else {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Update Failed",
                    text: "There was an error updating the service.",
                    confirmButtonText: "OK"
                });
              </script>';
    }

    mysqli_close($conn);
}
?>
