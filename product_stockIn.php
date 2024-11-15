<?php
$conn = new mysqli('localhost', 'root', '', 'bwsdb');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['addedProducts'])) {
    $addedProducts = json_decode($_POST['addedProducts'], true);

    if (is_array($addedProducts) && !empty($addedProducts)) {
        $stmt = $conn->prepare("UPDATE products SET quantity = quantity + ? WHERE id = ?");
        if ($stmt === false) {
            echo json_encode(["status" => "error", "message" => "Prepare statement failed: " . $conn->error]);
            exit;
        }

        foreach ($addedProducts as $productId => $productData) {
            if (isset($productData['quantity']) && is_numeric($productData['quantity'])) {
                $quantityToAdd = (int) $productData['quantity'];
                $stmt->bind_param("ii", $quantityToAdd, $productId);

                if (!$stmt->execute()) {
                    echo json_encode(["status" => "error", "message" => "Error updating product with ID $productId: " . $stmt->error]);
                    exit;
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Invalid quantity data for product ID $productId"]);
                exit;
            }
        }

        echo json_encode(["status" => "success", "message" => "Stock updated successfully!"]);
        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "No valid products to update."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No products to update."]);
}

$conn->close();
?>