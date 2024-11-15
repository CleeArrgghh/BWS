<?php
session_start();
include_once '../bws_ui/includes/header.php';

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
</header>

<!-- Sidebar Section -->
<div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar" style="width: 250px;">
    <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()"
        style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>

    <!-- User Interface Section -->
    <?php if (isset($_SESSION['username']) && isset($_SESSION['role'])): ?>
        <div class="user-interface text-center mb-4">
            <?php
            $profile_image = "../bws_ui/images/user_profile/default_logo.jpg";
            if (file_exists("../bws_ui/images/user_profile/" . $_SESSION['username'] . ".jpg")) {
                $profile_image = "../bws_ui/images/user_profile/" . $_SESSION['username'] . ".jpg";
            }
            ?>
            <img src="<?php echo $profile_image; ?>" alt="User  Profile" class="profile-icon rounded-circle mb-2" style="width: 100px; height: 100px; border: 2px solid #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
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
            <img src="../bws_ui/images/user_profile/default_logo.jpg" alt="User  Profile" class="profile-icon rounded-circle mb-2" style="width: 100px; height: 100px; border: 2px solid #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
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
            <a href="#booking" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-calendar-check fa-lg me-3 icon"></i>
                <span>Booking</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="#gallery" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-images fa-lg me-3 icon"></i>
                <span>Gallery</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="services.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-spa fa-lg me-3 icon"></i>
                <span>Services</span>
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


<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once '../bws_ui/includes/header.php';
include '../bws_ui/db_connection/db_connection.php'; // Database connection
?>

<style>
    /* Existing CSS */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');
    :root {
        --text-color: #333;
        --bg-color: #f0f2f5;
    }
    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
    }
    h1 {
        font-weight: 600;
        color: var(--primary-color);
    }
    .card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s, box-shadow 0.3s;
        background-color: var(--card-bg-color);
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
    .card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
    .card-title {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.5rem;
    }
    .card-body {
        background-color: var(--card-bg-color);
        padding: 25px;
    }
    .bg-light-purple {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: #fff;
    }
    .card-body p {
        margin-bottom: 0.75rem;
        color: #555;
        font-size: 1rem;
    }
    .gallery-img {
        height: 300px;
        object-fit: cover;
        width: 100%;
        transition: transform 0.5s;
    }
    .card:hover .gallery-img {
        transform: scale(1.1);
    }
    .gallery-description {
        color: #666;
        font-size: 0.95rem;
    }
    footer p {
        font-size: 1rem;
        color: #fff;
    }

    /* Status Dot CSS */
    .status-dot {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .status-available {
        background-color: green;
    }
    .status-not-available {
        background-color: red;
    }
</style>

<div class="container mt-5">
    <h1 class="text-center mb-5">Meet Our Team</h1>
    <div class="row">
        <?php
        // Query to fetch staff data with positions and descriptions
        $staffQuery = "SELECT s.staff_id, s.firstname, s.middlename, s.lastname, s.phone, s.address, s.dob, s.gender, 
               p.position_name, p.description, s.image_path, s.status 
               FROM tbl_staff s 
               JOIN tbl_positions p ON s.position_id = p.position_id";

            $result = $conn->query($staffQuery);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }


        // Loop through each staff member and display their info
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fullName = $row['firstname'] . ' ' . ($row['middlename'] ? $row['middlename'] . ' ' : '') . $row['lastname'];
                $gender = $row['gender'];
                $phone = $row['phone'];
                $position = $row['position_name'];
                $description = $row['description'];
                $imagePath = !empty($row['image_path']) ? '../bws_ui/staff_images/' . $row['image_path'] : '../bws_ui/staff_images/default-image.jpg';
                $statusDotClass = ($row['status'] === 'Available') ? 'status-available' : 'status-not-available';

                echo '<div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="' . $imagePath . '" class="gallery-img card-img-top" alt="' . $fullName . '">
                            <div class="card-body text-center">
                                <p><span class="status-dot ' . $statusDotClass . '"></span>' . $row['status'] . '</p>
                                <h5 class="card-title">' . $fullName . '</h5>
                                <p><strong>Gender:</strong> ' . $gender . '</p>
                                <p><strong>Contact:</strong> ' . $phone . '</p>
                                <p><strong>Position:</strong> ' . $position . '</p>
                                <p class="gallery-description"><strong>Description:</strong><br>' . $description . '</p>
                            </div>
                        </div>
                      </div>';
            }
        } else {
            echo '<p class="text-center">No staff members found.</p>';
        }
        ?>
    </div>
</div>


<footer class="text-center py-3 bg-light-purple">
    <p>&copy; 2024 Bernadette Wellness Spa. All rights reserved.</p>
</footer>

<?php include_once '../bws_ui/includes/footer.php'; ?> 
