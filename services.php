<?php
ob_start(); // Start output buffering
session_start(); // Make sure this is after output buffering
include_once '../bws_ui/includes/header.php';
include_once '../bws_ui/db_connection/db_connection.php';

// Initialize an array to store discounted services for the alert
$discountedServices = [];

// Check if there's a service to highlight
$highlightServiceId = isset($_GET['highlight_discount']) ? (int)$_GET['highlight_discount'] : null;
?>


<!-- Navbar Section -->
<header class="navbar bg-light shadow-sm">
    <div class="container d-flex align-items-center">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle btn btn-outline-danger me-3" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <!-- Logo (Centered) -->
        <a href="index.php" class="navbar-brand mx-auto text-dark">Bernadette Wellness Spa</a>
    </div>
</header>

<!-- Sidebar Section -->
<div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar" style="width: 250px;">
    <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()" style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>

    <!-- User Interface Section -->
    <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
        <div class="user-interface text-center mb-4">
            <?php
            $profile_image = "../bws_ui/images/user_profile/default_logo.jpg";
            if (file_exists("../bws_ui/images/user_profile/" . $_SESSION['username'] . ".jpg")) {
                $profile_image = "../bws_ui/images/user_profile/" . $_SESSION['username'] . ".jpg";
            }
            ?>
            <img src="<?php echo $profile_image; ?>" alt="User Profile" class="profile-icon rounded-circle mb-2" style="width: 100px; height: 100px; border: 2px solid #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <h6 class="text-dark mt-2"><?php echo $_SESSION['username']; ?></h6>
            <form action="upload_profile.php" method="post" enctype="multipart/form-data">
                <div class="input-group mb-3">
                    <input type="file" name="profile_image" accept=".jpg, .jpeg, .png" class="form-control" id="inputGroupFile02">
                    <label class="input-group-text" for="inputGroupFile02">Upload</label>
                </div>
                <button type="submit" class="btn btn-outline-primary btn-sm">Update Profile</button>
            </form>
            <a href="logout.php" class="btn btn-outline-danger btn-sm mt-2" onclick="return confirm('Are you sure you want to log out?')">Log Out</a>
        </div>
    <?php else: ?>
        <div class="user-interface text-center mb-4">
            <img src="../bws_ui/images/user_profile/default_logo.jpg" alt="User Profile" class="profile-icon rounded-circle mb-2" style="width: 100px; height: 100px; border: 2px solid #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <h6 class="text-dark mt-2">Guest</h6>
            <a href="../bws_ui/login/log_in.php" class="btn btn-outline-primary btn-sm mt-2">Log In</a>
        </div>
    <?php endif; ?>

    <ul class="list-unstyled">
        <li class="mb-3">
            <a href="index.php" id="homeLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-home fa-lg me-3 icon"></i>
                <span>Home</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="../bws_ui/booking/booking.php" id="bookingLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-calendar-check fa-lg me-3 icon"></i>
                <span>Booking</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="gallery.php" id="galleryLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-images fa-lg me-3 icon"></i>
                <span>Gallery</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="services.php" id="servicesLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-spa fa-lg me-3 icon"></i>
                <span>Services</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="../bws_ui/history.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-history fa-lg me-3 icon"></i>
                <span>History</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="#aboutus" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-info-circle fa-lg me-3 icon"></i>
                <span>About Us</span>
            </a>
        </li>
    </ul>
</div>

<div class="py-5 bg-light">
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card text-center mb-4">
                    <div class="card-body">
                        <h1 class="card-title custom-large-text moving-text">Bernadette Wellness Spa</h1>
                        <h1 class="card-title custom-large-text moving-text">Package and Price List</h1>
                    </div>
                </div>
            </div>
        </div>

<!-- Filtered Services Section -->
<div class="container" id="serviceList">
    <div class="row">
        <?php
        $query = "
            SELECT s.id, s.name AS service_name, s.description, s.image, 
                   s.price AS original_price, 
                   d.discount_percentage, 
                   CASE 
                       WHEN CURRENT_TIMESTAMP BETWEEN d.start_time AND d.end_time 
                       THEN s.price - (s.price * d.discount_percentage / 100) 
                       ELSE NULL 
                   END AS discounted_price 
            FROM services s 
            LEFT JOIN discounts d ON s.id = d.service_id AND CURRENT_TIMESTAMP BETWEEN d.start_time AND d.end_time 
            GROUP BY s.id 
            ORDER BY s.name";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $image = htmlspecialchars($row['image']);
                $imagePath = '../bws_ui/services_images/' . $image;
                $serviceId = htmlspecialchars($row['id']);
                $description = htmlspecialchars($row['description']);
                $originalPrice = $row['original_price'];
                $discountedPrice = $row['discounted_price'];
                $discountPercentage = $row['discount_percentage'];
                $maxWords = 30;
                $words = explode(' ', $description);
                if (count($words) > $maxWords) {
                    $description = implode(' ', array_slice($words, 0, $maxWords)) . '...';
                }

                echo '<div class="col-lg-4 col-md-6 col-sm-12 mb-4 d-flex align-items-stretch">';
                echo '  <div class="card h-100">';

                if (!empty($image) && file_exists($imagePath)) {
                    echo '    <img src="' . $imagePath . '" class="card-img-top fixed-image" alt="' . htmlspecialchars($row['service_name']) . '">';
                }

                echo '    <div class="card-body d-flex flex-column">';
                echo '      <h5 class="card-title">' . htmlspecialchars($row['service_name']) . '</h5>';
                echo '      <p class="card-text flex-grow-1">' . $description . '</p>';

                if ($discountedPrice !== null) {
                    // Show original price with strikethrough and discounted price if a discount is active
                    echo '<p class="card-text text-muted" style="text-decoration: line-through; color: #999;">Original Price: ₱' . number_format($originalPrice, 2) . '</p>';
                    echo '<p class="card-text text-danger"><strong>Discounted Price: ₱' . number_format($discountedPrice, 2) . '</strong></p>';
                    echo '<p class="card-text"><small class="text-muted">(' . $discountPercentage . '% OFF)</small></p>';
                } else {
                    // Show only the original price if there's no active discount
                    echo '<p class="card-text"><strong>Original Price: ₱' . number_format($originalPrice, 2) . '</strong></p>';
                }

                echo '      <a href="../bws_ui/booking/booking.php?service_id=' . $serviceId . '&discounted_price=' . ($discountedPrice ?: $originalPrice) . '" class="btn btn-primary mt-auto">
                                <i class="fas fa-calendar-alt"></i> Book Now
                            </a>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Display each service's details as you did before
                    // (Include code to show discounted or original prices based on discount availability)
                }
            } else {
                echo '<p class="text-center">No services available for this category.</p>';
            }
            
        }
        mysqli_close($conn);
        ?>
    </div>
</div>

<!-- Discount Modal Notification -->
<div id="discountModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Current Discounts Available!</h2>
        <ul id="discountList"></ul>
        <button onclick="closeModal()" class="btn btn-success mt-3">Got it!</button>
    </div>
</div>
<div class="col-lg-4 col-md-6 col-sm-12 mb-4 d-flex align-items-stretch <?php echo $serviceId == $highlightServiceId ? 'highlighted-service' : ''; ?>">

<style>
    .category-section {
        background-color: #fff;
        padding: 2rem;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        margin-bottom: 3rem;
        /* Added margin between categories */
    }

    .category-title {
        color: #333;
        font-weight: bold;
        text-align: center;
        /* Center category title */
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card-img-top {
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .fixed-image {
        height: 200px;
        /* Fixed image height */
        object-fit: cover;
        /* Maintain aspect ratio and cover the entire area */
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        color: #333;
        font-weight: bold;
    }

    .card-text {
        color: #666;
    }

    .btn-primary {
        background-color: #9966ff;
        /* Purple color */
        border: none;
        border-radius: 5px;
    }

    .btn-primary:hover {
        background-color: #804dff;
        /* Darker purple on hover */
    }

    .moving-text {
        animation: moveText 5s linear infinite;
    }

    @keyframes moveText {
        0% {
            transform: translateX(0);
        }

        50% {
            transform: translateX(10px);
        }

        100% {
            transform: translateX(0);
        }
    }

    /* Modal Styling */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        position: relative;
        background-color: #ffffff;
        margin: 15% auto;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-width: 500px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        text-align: center;
        animation: slideDown 0.5s ease;
    }

    .modal-content h2 {
        color: #6a1b9a;
    }

    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        color: #aaa;
        font-size: 24px;
        cursor: pointer;
    }

    .close:hover {
        color: #000;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    @keyframes slideDown {
    0% {
        transform: translateY(-50%);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
    }
    .highlighted-service {
        border: 2px solid #ff6600;
        background-color: #fff8e1;
        padding: 10px;
    }
</style>

<footer class="text-center py-3 bg-light-purple">
    <p>&copy; 2024 Bernadette Wellness Spa. All rights reserved.</p>
</footer>


<script src="../bws_ui/resources/bootstrap/js/bootstrap.min.js"></script>
<script src="../bws_ui/resources/bootstrap/js/toggle.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Part 1: Display modal with discounted services
        const discountedServices = <?php echo json_encode($discountedServices); ?>;
        if (discountedServices.length > 0) {
            const discountList = document.getElementById("discountList");
            discountedServices.forEach(service => {
                const listItem = document.createElement("li");
                listItem.innerHTML = `<strong>${service.name}</strong>: ${service.discount}% OFF - ₱${service.discounted_price}`;
                discountList.appendChild(listItem);
            });
            document.getElementById("discountModal").style.display = "block";
        }

        // Close modal function
        function closeModal() {
            document.getElementById("discountModal").style.display = "none";
        }

        // Part 2: Preselect service if ID is provided via URL
        const preselectedServiceId = "<?php echo $preselectedServiceId; ?>";
        const preselectedServicePrice = "<?php echo isset($_GET['discounted_price']) ? $_GET['discounted_price'] : ''; ?>";

        if (preselectedServiceId) {
            const serviceSelect = document.getElementById('serviceSelect');

            // Select the service in the dropdown
            for (let i = 0; i < serviceSelect.options.length; i++) {
                if (serviceSelect.options[i].value === preselectedServiceId) {
                    serviceSelect.selectedIndex = i;
                    break;
                }
            }

            // Add the selected service to the list of selected services
            if (preselectedServicePrice) {
                addService();
            }
        }

        // Make closeModal function accessible from the HTML
        window.closeModal = closeModal;
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const preselectedServiceId = "<?php echo $preselectedServiceId; ?>";
    const preselectedServicePrice = parseFloat("<?php echo $preselectedServicePrice; ?>");

    if (preselectedServiceId) {
        const serviceSelect = document.getElementById('serviceSelect');

        // Select the preselected service in the dropdown
        for (let i = 0; i < serviceSelect.options.length; i++) {
            if (serviceSelect.options[i].value === preselectedServiceId) {
                serviceSelect.selectedIndex = i;
                break;
            }
        }

        // Add the selected service to the list of selected services
        if (preselectedServicePrice) {
            addService(preselectedServiceId, serviceSelect.options[serviceSelect.selectedIndex].text, preselectedServicePrice);
        }
    }
});

function addService(serviceId, serviceText, servicePrice) {
    const selectedServiceId = serviceId || document.getElementById('serviceSelect').value;
    const selectedServiceText = serviceText || document.getElementById('serviceSelect').options[document.getElementById('serviceSelect').selectedIndex].text;
    const selectedServicePrice = servicePrice || parseFloat(document.getElementById('serviceSelect').options[document.getElementById('serviceSelect').selectedIndex].getAttribute('data-price')) || 0;

    if (selectedServiceId && !selectedServiceIds.has(selectedServiceId)) {
        selectedServiceIds.add(selectedServiceId);
        totalPrice += selectedServicePrice;

        const serviceDiv = document.createElement('div');
        serviceDiv.classList.add('selected-service-item', 'mt-2', 'p-2', 'border', 'rounded', 'bg-light');
        serviceDiv.setAttribute('data-id', selectedServiceId);
        serviceDiv.innerHTML = `
            <span>${selectedServiceText} - ₱${selectedServicePrice.toFixed(2)}</span>
            <button type="button" class="btn btn-danger btn-sm ms-2" onclick="removeService('${selectedServiceId}', ${selectedServicePrice})">
                <i class="fas fa-minus"></i> Remove
            </button>
        `;
        document.getElementById('selectedServices').appendChild(serviceDiv);

        disableSelectedServices();
        updateTotalPrice();
    }
}
</script>   
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const discountedServices = <?php echo json_encode($discountedServices); ?>;
        if (discountedServices.length > 0) {
            const discountList = document.getElementById("discountList");
            discountedServices.forEach(service => {
                const listItem = document.createElement("li");
                listItem.innerHTML = `<strong>${service.name}</strong>: ${service.discount}% OFF - ₱${service.discounted_price}`;
                discountList.appendChild(listItem);
            });
            document.getElementById("discountModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("discountModal").style.display = "none";
        }
        window.closeModal = closeModal;
    });
</script>

<?php include_once '../bws_ui/includes/footer.php'; ?>