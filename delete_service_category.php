<?php
include_once '../bws_ui/db_connection/db_connection.php';

// Initialize response variables
$title = '';
$text = '';
$icon = '';

// Check if the ID is provided and is numeric
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $categoryId = intval($_GET['id']);

    // Retrieve the image filename associated with the category
    $selectQuery = "SELECT image FROM service_categories WHERE id = ?";
    if ($stmt = $conn->prepare($selectQuery)) {
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $stmt->bind_result($image);
        $stmt->fetch();
        $stmt->close();

        // Check if the image exists in the folder
        $imagePath = "../bws_ui/images/" . $image;
        if (!empty($image) && file_exists($imagePath)) {
            // Delete the image file
            if (!unlink($imagePath)) {
                $title = 'Error!';
                $text = 'Failed to delete the associated image.';
                $icon = 'error';
                echo json_encode(['title' => $title, 'text' => $text, 'icon' => $icon]);
                exit;
            }
        }

        // Now prepare the delete query for the category
        $deleteQuery = "DELETE FROM service_categories WHERE id = ?";
        if ($stmt = $conn->prepare($deleteQuery)) {
            $stmt->bind_param("i", $categoryId);

            if ($stmt->execute()) {
                $title = 'Success!';
                $text = 'Category and associated image deleted successfully!';
                $icon = 'success';
            } else {
                $title = 'Error!';
                $text = 'Failed to delete category.';
                $icon = 'error';
            }

            $stmt->close(); // Close the statement
        } else {
            $title = 'Error!';
            $text = 'Category deletion query preparation failed.';
            $icon = 'error';
        }
    } else {
        $title = 'Error!';
        $text = 'Failed to retrieve category information.';
        $icon = 'error';
    }
} else {
    $title = 'Error!';
    $text = 'Invalid category ID.';
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
    <title>Delete Category</title>
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
            window.location.href = 'add_category.php'; // Redirect to the appropriate page for categories
        });
    </script>
</body>
</html>";
