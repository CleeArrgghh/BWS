<?php
include_once '../bws_ui/db_connection/db_connection.php';

// Define function to add a new product
function addProduct($productName, $productQuantity, $serviceCategory, $productDescription, $productImagePath) {
    global $conn; // Use the database connection defined elsewhere
    
    // Prepare the SQL statement
    $query = "INSERT INTO products (name, description, quantity, category_id, image) VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssiss", $productName, $productDescription, $productQuantity, $serviceCategory, $productImagePath);

    // Execute and return result
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize inputs
    $productName = $_POST['productName'];
    $productQuantity = $_POST['productQuantity'];
    $serviceCategory = $_POST['serviceCategory'];
    $productDescription = $_POST['productDescription'];
    $productImagePath = "";

    // Handle file upload
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../bws_ui/images/';
        $uploadFile = $uploadDir . basename($_FILES['productImage']['name']);
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES['productImage']['tmp_name'], $uploadFile)) {
            $productImagePath = basename($_FILES['productImage']['name']);
        } else {
            echo "Error: File upload failed.";
            exit;
        }
    }

    // Add product to the database
    if (addProduct($productName, $productQuantity, $serviceCategory, $productDescription, $productImagePath)) {
        echo "<script>
                alert('Product added successfully');
                window.location.href = 'add_product.php'; // Redirect to the products page
              </script>";
    } else {
        echo "<script>
                alert('Error: Could not add product');
                window.history.back();
              </script>";
    }
}
?>
