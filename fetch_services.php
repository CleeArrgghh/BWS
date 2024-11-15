<?php
include_once '../bws_ui/db_connection/db_connection.php';

if (isset($_POST['categoryId'])) {
    $categoryId = intval($_POST['categoryId']);

    // Fetch services based on category
    $query = "SELECT * FROM services WHERE category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="row">';
        while ($service = $result->fetch_assoc()) {
            $serviceName = htmlspecialchars($service['name']);
            $serviceDescription = htmlspecialchars($service['description']);
            $servicePrice = htmlspecialchars($service['price']);
            $serviceImage = htmlspecialchars($service['image']); // Ensure this path is correct

            // Sanitize image URL for security
            $serviceImage = filter_var($serviceImage, FILTER_SANITIZE_URL);
?>
            <div class="col-md-4">
                <div class="card mx-2 mb-3" style="width: 18rem; border-radius: 15px;">
                    <img src="<?php echo $serviceImage; ?>" alt="Service Image" class="img-fluid" style="border-radius: 15px 15px 0 0; height: 200px; object-fit: cover;">
                    <div class="card-body text-center">
                        <h5 class="card-title"><?php echo $serviceName; ?></h5>
                        <p class="card-text"><?php echo $serviceDescription; ?></p>
                        <p class="card-text">Price: $<?php echo $servicePrice; ?></p>
                    </div>
                </div>
            </div>
<?php
        }
        echo '</div>';
    } else {
        echo '<p>No services found for this category.</p>';
    }

    $stmt->close();
    $conn->close();
}
?>
