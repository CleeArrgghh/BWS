<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bwsdb";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure 'removedProducts' is provided
    if (!isset($_POST['removedProducts'])) {
        echo json_encode(['status' => 'error', 'message' => 'No products data provided']);
        exit;
    }

    // Decode the JSON of removed products
    $removedProducts = json_decode($_POST['removedProducts'], true);

    // Check if decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON data']);
        exit;
    }

    // Database connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Loop through removed products and update their stock
    foreach ($removedProducts as $productId => $product) {
        // Make sure each product has the necessary fields
        if (!isset($product['name'], $product['quantity'])) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid product data for product ID: ' . $productId]);
            exit;
        }

        $name = $product['name'];
        $quantity = $product['quantity'];

        // Check the current stock
        $query = "SELECT quantity FROM products WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row) {
            $currentStock = $row['quantity'];

            // Ensure that stock doesn't go below 0
            if ($currentStock >= $quantity) {
                $newStock = $currentStock - $quantity;

                // Update the stock in the database
                $updateQuery = "UPDATE products SET quantity = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                $updateStmt->bind_param("ii", $newStock, $productId);
                $updateStmt->execute();
            } else {
                echo json_encode(['status' => 'error', 'message' => "Not enough stock for product: $name"]);
                exit;
            }
        } else {
            // Product ID not found in the database
            echo json_encode(['status' => 'error', 'message' => "Product with ID $productId not found"]);
            exit;
        }
    }

    // Success
    echo json_encode(['status' => 'success']);
    $conn->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>