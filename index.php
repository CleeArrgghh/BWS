<?php
session_start();
include_once '../bws_ui/includes/header.php';
include_once '../bws_ui/db_connection/db_connection.php';

$query = "
    SELECT s.id, s.name AS service_name, s.description, s.image, 
           s.price AS original_price, 
           d.discount_percentage, 
           s.price - (s.price * d.discount_percentage / 100) AS discounted_price 
    FROM services s 
    JOIN discounts d ON s.id = d.service_id 
    WHERE CURRENT_TIMESTAMP BETWEEN d.start_time AND d.end_time 
    ORDER BY s.name";
$result = mysqli_query($conn, $query);

$activeDiscounts = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $activeDiscounts[] = [
            'name' => $row['service_name'],
            'discount' => $row['discount_percentage'],
            'discounted_price' => number_format($row['discounted_price'], 2)
        ];
    }
}
?>
<?php
// If there are active discounts, set a flag for JavaScript
$hasDiscounts = count($activeDiscounts) > 0;
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
        <span id="currentDateTime" class="text-dark"></span>
    </div>
</header>
<!-- Sidebar Section -->
<div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar" style="width: 250px;">
    <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()" style="font-size: 1.5rem; position : absolute; right: 15px; top: 15px;">&times;</button>

    <!-- User Interface Section -->
    <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
        <div class="user-interface text-center p-4 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
            <?php
            $profile_image = "../bws_ui/images/user_profile/default_logo.jpg";
            if (file_exists("../bws_ui/images/user_profile/" . $_SESSION['username'] . ".jpg")) {
                $profile_image = "../bws_ui/images/user_profile/" . $_SESSION['username'] . ".jpg";
            }
            // Append a query parameter to bust the cache
            $profile_image_url = $profile_image . '?' . time();
            ?>
            <div class="profile-image-container position-relative mb-3">
                <img src="<?php echo $profile_image_url; ?>" alt="User Profile" class="profile-icon rounded-circle shadow-sm" style="width: 120px; height: 120px; border: 3px solid #6c757d; box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);">
            </div>
            <h5 class="text-primary fw-bold mt-2"><?php echo $_SESSION['username']; ?></h5>
            <form action="upload_profile.php" method="post" enctype="multipart/form-data" class="mt-3">
                <div class="input-group mb-3">
                    <input type="file" name="profile_image" accept=".jpg, .jpeg, .png" class="form-control" id="inputGroupFile02">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Update Profile</button>
            </form>
            <a href="logout.php" class="btn btn-danger btn-sm mt-3">Log Out</a>
        </div>
    <?php else: ?>
        <div class="user-interface text-center p-4 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
            <img src="../bws_ui/images/user_profile/default_logo.jpg" alt="User Profile" class="profile-icon rounded-circle shadow-sm mb-2" style="width: 120px; height: 120px; border: 3px solid #6c757d; box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);">
            <h5 class="text-secondary mt-2">Guest</h5>
        </div>
    <?php endif; ?>

    <ul class="list-unstyled">
        <li class="mb-3">
            <a href="#index" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
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
            <a href="../bws_ui/history/history.php" id="historyLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
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


<!-- Carousel Section -->
<section class="carousel-section">
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-wrap="true">
        <div class="carousel-inner">
            <!-- First Slide -->
            <div class="carousel-item active">
                <img src="../bws_ui/images/carousel images/1.jpg" class="d-block w-100 carousel-image" alt="Massage">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <h3 class="slide-title">Welcome to Bernadette Wellness Spa</h3>
                    <p class="slide-text">Experience the best in wellness Here In Tangub</p>
                </div>
            </div>
            <!-- Second Slide -->
            <div class="carousel-item">
                <img src="../bws_ui/images/carousel images/slide2.jpg" class="d-block w-100 carousel-image" alt="Facial">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <h3 class="slide-title">Pamper Yourself with Our Facial Treatments</h3>
                    <p class="slide-text">Our facials are designed to rejuvenate your skin and soul.</p>
                </div>
            </div>
            <!-- Third Slide -->
            <div class="carousel-item">
                <img src="../bws_ui/images/carousel images/slide3.jpg" class="d-block w-100 carousel-image" alt="Foot Spa">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 text-center">
                    <h3 class="slide-title">Relax Your Feet with Our Foot Spa</h3>
                    <p class="slide-text">Treat your feet to a soothing experience like no other.</p>
                </div>
            </div>
            <!-- Fourth Slide -->
            <div class="carousel-item">
                <img src="../bws_ui/images/carousel images/slide1.jpg" class="d-block w-100 carousel-image" alt="Body Scrub">
            </div>
        </div>
        <!-- Carousel Controls -->
        <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </a>
    </div>
</section>
<!-- End Carousel Section -->

<!-- Home Section -->
<div id="home" class="text-center py-5 bg-light fade-in">
    <div class="d-flex flex-column align-items-center" style="padding: 15px 0; margin-top: -20px;">
        <h2 style="font-weight: bold; color: #6f42c1; font-size: 3rem; animation: pulse 1.5s infinite;">
            Welcome to Bernadette Wellness Spa
        </h2>

        <?php if (isset($_SESSION['fullname'])): ?>
            <p style="font-size: 1.5rem; color: #6f42c1; margin: 0;">
                Hello, <strong><?php echo htmlspecialchars($_SESSION['fullname']); ?></strong>! Let's relax and recharge.
            </p>
        <?php else: ?>
            <p style="font-size: 1.5rem; color: #6f42c1;">
                Discover <strong>ultimate relaxation</strong> and <strong>rejuvenation</strong> like never before!
            </p>
            <div class="d-flex">
                <a href="../bws_ui/login/log_in.php" id="loginButton" class="btn btn-light me-2 btn-lg" style="padding: 15px 30px; font-size: 18px; color: #6f42c1; background-color: white; border: 2px solid #6f42c1; transition: background-color 0.3s, color 0.3s;"
                    onmouseover="this.style.backgroundColor='#6f42c1'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='white'; this.style.color='#6f42c1';">
                    Log In
                </a>
                <a href="../bws_ui/login/registration.php" id="signupButton" class="btn btn-light btn-lg" style="padding: 15px 30px; font-size: 18px; color: #6f42c1; background-color: white; border: 2px solid #6f42c1; transition: background-color 0.3s, color 0.3s;"
                    onmouseover="this.style.backgroundColor='#6f42c1'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='white'; this.style.color='#6f42c1';">
                    Sign Up
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Service Categories Section -->
<section class="service-categories">
    <div class="container">
        <?php
        include_once '../bws_ui/db_connection/db_connection.php';
        $query = "SELECT * FROM service_categories";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            $imagePath = htmlspecialchars($row['image']);
            $categoryId = htmlspecialchars($row['id']);
            $description = htmlspecialchars($row['description']);
            $maxWords = 30;
            $words = explode(' ', $description);
            if (count($words) > $maxWords) {
                $description = implode(' ', array_slice($words, 0, $maxWords)) . '...';
            }

            echo '<div class="row category-row align-items-center">';
            echo '    <div class="col-md-6 text-section">';
            echo '        <h2>' . htmlspecialchars($row['name']) . '</h2>';
            echo '        <p>' . $description . '</p>';
            echo '<a href="services.php?category_id=' . $categoryId . '" class="btn explore-button">See More</a>';
            echo '    </div>';
            echo '    <div class="col-md-6 image-section">';
            echo '        <img src="../bws_ui/images/' . $imagePath . '" alt="Category Image" class="img-fluid">';
            echo '    </div>';
            echo '</div>';
            echo '<hr>';
        }
        mysqli_close($conn);
        ?>
    </div>
</section>




<!-- Find Us Section -->
<div id="find-us" class="find-us-section text-center py-5 bg-light slide-in-section">
    <div class="container">
        <div class="row d-flex align-items-center">
            <!-- Google Map Embed -->
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="map-container">
                    <!-- Replace this iframe src with your actual Google Map embed URL -->
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.361532120258!2d123.74697877405764!3d8.064556603341435!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32544c56ee9bef25%3A0x739435760f08ae95!2sPark%20N%20Go%20Bakeshop!5e0!3m2!1sen!2sph!4v1724642281481!5m2!1sen!2sph"
                        width="100%"
                        height="450"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
            <!-- Location Description -->
            <div class="col-lg-5">
                <h2 class="mb-3">Find Us</h2>
                <p class="lead mb-4">Located near Park 'N Go Bakeshop, on First Street, Tangub City, 7200 Philippines.</p>
                <address>
                    <strong>Bernadette Wellness Spa</strong><br>
                    1st st, Tangub City,<br>
                    Misamis Occidental, 7200 Philippines<br>
                    <a href="mailto:bernadettewellnessspa@gmail.com">bernadettewellnessspa@gmail.com</a><br>
                    <a href="tel:+1234567890">+63 91234567890</a>
                </address>
            </div>
        </div>
    </div>
</div>


<!-- Policies Section -->
<div class="policies py-5 bg-light slide-in-section">
    <h2 class="mb-4 text-center display-4">Terms and Policies</h2> <!-- Increased font size -->
    <div class="container">
        <div class="accordion" id="policiesAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBookingPolicy" aria-expanded="true" aria-controls="collapseBookingPolicy">
                        <span class="h5">Booking Policy</span> <!-- Increased font size -->
                    </button>
                </h2>
                <div class="accordion-collapse collapse show" id="collapseBookingPolicy" aria-labelledby="headingBookingPolicy" data-bs-parent="#policiesAccordion">
                    <div class="accordion-body fs-5"> <!-- Increased font size -->
                        <ul class="list-unstyled">
                            <li>Reservations must be made 24 hours in advance.</li>
                            <li>Cancellations require a 12-hour notice.</li>
                            <li>Late arrivals will be subject to reduced session time.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHealthSafety" aria-expanded="false" aria-controls="collapseHealthSafety">
                        <span class="h5">Health and Safety</span> <!-- Increased font size -->
                    </button>
                </h2>
                <div class="accordion-collapse collapse" id="collapseHealthSafety" aria-labelledby="headingHealthSafety" data-bs-parent="#policiesAccordion">
                    <div class="accordion-body fs-5"> <!-- Increased font size -->
                        <ul class="list-unstyled">
                            <li>Please inform us of any allergies or medical conditions.</li>
                            <li>We adhere to strict sanitation and hygiene practices.</li>
                            <li>All therapists are certified and trained in first aid.</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEtiquette" aria-expanded="false" aria-controls="collapseEtiquette">
                        <span class="h5">Etiquette</span> <!-- Increased font size -->
                    </button>
                </h2>
                <div class="accordion-collapse collapse" id="collapseEtiquette" aria-labelledby="headingEtiquette" data-bs-parent="#policiesAccordion">
                    <div class="accordion-body fs-5"> <!-- Increased font size -->
                        <ul class="list-unstyled">
                            <li>Kindly arrive 10 minutes before your appointment.</li>
                            <li>Mobile phones should be turned off or on silent.</li>
                            <li>We request a quiet and peaceful environment for all guests.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discount Announcement Modal -->
<?php if ($hasDiscounts): ?>
<div id="discountAnnouncement" class="modal" style="display: none;">
    <div class="modal-content" style="background-color: #fff; padding: 30px; border-radius: 8px; width: 90%; max-width: 500px; box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2); text-align: center;">
        <h2 style="color: #333; font-family: 'Arial', sans-serif; font-size: 24px; margin-bottom: 10px;">Exclusive Discounts Just Started!</h2>
        <p style="color: #555; font-size: 18px; margin-bottom: 20px; font-family: 'Arial', sans-serif;">Check out our special discounted prices.</p>
        <button onclick="window.location.href='discounted_services.php'" class="btn btn-primary" style="background-color: #28a745; color: #fff; padding: 12px 20px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer; margin-bottom: 10px;">View All Discounts</button>
        <button onclick="closeAnnouncement()" class="btn" style="background-color: #dc3545; color: #fff; padding: 10px 20px; font-size: 16px; border: none; border-radius: 5px; cursor: pointer;">Close</button>
    </div>
</div>
<?php endif; ?>

<script>
    function openAnnouncement() {
        const modal = document.getElementById('discountAnnouncement');
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeAnnouncement() {
        const modal = document.getElementById('discountAnnouncement');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    // Show the modal only if discounts are available
    window.onload = function() {
        <?php if ($hasDiscounts): ?>
        openAnnouncement();
        <?php endif; ?>
    };
</script>

<!-- Footer Section -->
<footer class="text-center py-3 bg-light-purple">
    <p>&copy; 2024 Bernadette Wellness Spa. All rights reserved.</p>
</footer>

<!-- Other scripts and content remain as they are -->


<!-- Scroll to Footer Button -->
<button id="scrollToFooterBtn" class="btn btn-secondary" onclick="scrollToFooter()" style="position: fixed; bottom: 70px; right: 30px; display: none;">
    ↓ Bottom
</button>

<script>
    // Scroll to Top Function
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // Scroll to Footer Function
    function scrollToFooter() {
        const footer = document.querySelector('footer');
        footer.scrollIntoView({
            behavior: 'smooth'
        });
    }

    // Show buttons on scroll
    window.onscroll = function() {
        const button = document.getElementById("scrollToTopBtn");
        const footerButton = document.getElementById("scrollToFooterBtn");

        // Show scroll to top button
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            button.style.display = "block";
        } else {
            button.style.display = "none";
        }

        // Show scroll to footer button
        const footer = document.querySelector('footer');
        if (window.scrollY + window.innerHeight < document.body.scrollHeight - footer.offsetHeight) {
            footerButton.style.display = "block";
        } else {
            footerButton.style.display = "none";
        }
    };

    document.addEventListener("DOMContentLoaded", function() {
        const serviceSection = document.querySelector('.service-categories');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    serviceSection.classList.add('visible'); // Add the visible class when in view
                    observer.unobserve(serviceSection); // Stop observing after it becomes visible
                }
            });
        });

        observer.observe(serviceSection); // Start observing the service section
    });
</script>

<script>
    function toggleHistoryDropdown() {
        const dropdown = document.getElementById('historyDropdown');
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
        } else {
            dropdown.style.display = 'none';
        }
    }
</script>
<script>
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

<style>
/* Modal Overlay */
.modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Button Hover Effect */
.modal-content .btn-primary:hover {
    background-color: #218838;
}
</style>

<script>
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
        document.getElementById('currentDateTime').textContent = now.toLocaleDateString('en-US', options);
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

<?php include_once '../bws_ui/includes/footer.php'; ?>