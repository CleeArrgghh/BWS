<?php
include_once '../bws_ui/db_connection/db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['productId'];
    $name = $_POST['productName'];
    $quantity = $_POST['productQuantity'];
    $category = $_POST['serviceCategory'];
    $description = $_POST['productDescription'];
    $imagePath = null;

    // Handle new image upload if provided
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../bws_ui/images/";
        $imagePath = basename($_FILES['productImage']['name']);
        $fullPath = $targetDir . $imagePath;

        if (!move_uploaded_file($_FILES['productImage']['tmp_name'], $fullPath)) {
            showSweetAlert('Oops...', 'Error uploading image.', 'error');
            exit;
        }
    }

    // Determine product status based on quantity
    $productStatus = $quantity >= 6 ? 'Available' : ($quantity > 0 ? 'Low Stock' : 'Out of Stock');

    // Update query based on whether a new image was uploaded
    if ($imagePath) {
        $query = "UPDATE products SET name = ?, description = ?, quantity = ?, category_id = ?, status = ?, image = ? WHERE id = ?";
    } else {
        $query = "UPDATE products SET name = ?, description = ?, quantity = ?, category_id = ?, status = ? WHERE id = ?";
    }

    // Prepare the statement and check for errors
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters based on whether an image was uploaded
    if ($imagePath) {
        $stmt->bind_param("ssisssi", $name, $description, $quantity, $category, $productStatus, $imagePath, $id);
    } else {
        $stmt->bind_param("ssissi", $name, $description, $quantity, $category, $productStatus, $id);
    }

    // Execute the query and show SweetAlert notification
    if ($stmt->execute()) {
        showSweetAlert('Success', 'Product updated successfully!', 'success');
    } else {
        showSweetAlert('Oops...', 'Error: ' . $stmt->error, 'error');
    }

    $stmt->close();
    $conn->close();
} else {
    showSweetAlert('Invalid Request', 'Invalid request method.', 'error');
}

function showSweetAlert($title, $text, $icon) {
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>{$title}</title>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '{$title}',
                    text: '{$text}',
                    icon: '{$icon}',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'add_product.php';
                });
            });
        </script>
    </body>
    </html>";
}
?>
