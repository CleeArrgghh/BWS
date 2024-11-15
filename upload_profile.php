<?php
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['role'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
        $username = $_SESSION['username'];
        $profile_image = $_FILES['profile_image'];
        $image_name = $profile_image['name'];
        $image_type = $profile_image['type'];
        $image_size = $profile_image['size'];
        $image_tmp_name = $profile_image['tmp_name'];

        // Check for valid image types
        if ($image_type == "image/jpeg" || $image_type == "image/jpg" || $image_type == "image/png") {
            // Check if the image size is less than or equal to 2MB (2097152 bytes)
            if ($image_size <= 2097152) {
                $new_image_name = $username . ".jpg";
                $upload_path = "../bws_ui/images/user_profile/" . $new_image_name;

                // Check if the uploads directory exists
                if (!is_dir(dirname($upload_path))) {
                    echo "Uploads directory does not exist.";
                } else {
                    // Move the uploaded file to the uploads directory
                    if (move_uploaded_file($image_tmp_name, $upload_path)) {
                        // Redirect back to the index page with cache busting to load the new image
                        header('Location: index.php?updated=true');
                        exit;
                    } else {
                        echo "Error uploading image.";
                    }
                }
            } else {
                echo "Image size is too large. Maximum size is 2MB.";
            }
        } else {
            echo "Invalid image type. Only JPEG, JPG, and PNG formats are allowed.";
        }
    }
} else {
    header('Location: index.php');
    exit;
}
?>
