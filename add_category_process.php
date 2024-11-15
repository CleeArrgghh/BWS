<?php
include_once '../bws_ui/db_connection/db_connection.php';

$title = '';
$text = '';
$icon = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = isset($_POST['categoryId']) ? $_POST['categoryId'] : null;
    $name = $_POST['categoryName'];
    $description = $_POST['categoryDescription'];
    
    // Initialize variables
    $imagePath = null;

    // Handle file upload
    if (isset($_FILES['categoryImage']) && $_FILES['categoryImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../bws_ui/images/'; // Directory where images will be stored
        $imagePath = basename($_FILES['categoryImage']['name']); // Save only the filename
        $fullPath = $uploadDir . $imagePath; // Full server path

        if (!move_uploaded_file($_FILES['categoryImage']['tmp_name'], $fullPath)) {
            $title = 'Error!';
            $text = 'Unable to upload file.';
            $icon = 'error';
            showSweetAlertAndRedirect($title, $text, $icon);
            exit;
        }
    }

    // Construct query based on whether an ID is provided
    if ($id) {
        // Update existing category
        $query = "UPDATE service_categories SET name = ?, description = ?" . ($imagePath ? ", image = ?" : "") . " WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($imagePath) {
            $stmt->bind_param("sssi", $name, $description, $imagePath, $id);
        } else {
            $stmt->bind_param("ssi", $name, $description, $id);
        }
    } else {
        // Insert new category
        $query = "INSERT INTO service_categories (name, description, image) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $name, $description, $imagePath);
    }

    // Execute the query
    if ($stmt->execute()) {
        $title = 'Success!';
        $text = $id ? 'Category updated successfully!' : 'Category added successfully!';
        $icon = 'success';
    } else {
        $title = 'Error!';
        $text = 'There was an issue saving the data.';
        $icon = 'error';
    }

    // Show SweetAlert and redirect
    showSweetAlertAndRedirect($title, $text, $icon);

    // Close the database connection
    $stmt->close();
    $conn->close();
}

// Function to display SweetAlert and redirect
function showSweetAlertAndRedirect($title, $text, $icon) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>{$title}</title>
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
                window.location.href = 'add_category.php';
            });
        </script>
    </body>
    </html>";
}
?>
