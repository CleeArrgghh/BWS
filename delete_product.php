<?php
include_once '../bws_ui/db_connection/db_connection.php';

// Check if the request method is GET, the id is set, and it’s a valid integer greater than 0
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0) {
    // Retrieve and sanitize the product ID from the GET request
    $productId = (int)$_GET['id'];

    // Create a new database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare delete statement to delete only the product with the specified ID
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $productId);

    // Execute the delete query
    if ($stmt->execute()) {
        // Success response
        showSweetAlertAndRedirect('Deleted!', 'Product deleted successfully!', 'success', 'add_product.php');
    } else {
        // Error response
        showSweetAlertAndRedirect('Oops...', 'Error deleting product: ' . $stmt->error, 'error', null);
    }

    // Close connections
    $stmt->close();
    $conn->close();
} else {
    // Invalid request method, missing, or invalid product ID
    showSweetAlertAndRedirect('Invalid Request', 'Invalid or missing product ID.', 'error', 'add_product.php');
}

// Function to display SweetAlert and optionally redirect
function showSweetAlertAndRedirect($title, $text, $icon, $redirectUrl) {
    $redirectScript = $redirectUrl ? "window.location.href = '{$redirectUrl}';" : '';
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
                {$redirectScript}
            });
        </script>
    </body>
    </html>";
}
