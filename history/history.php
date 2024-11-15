<?php
session_start();
include '../../bws_ui/db_connection/db_connection.php';
include '../../bws_ui/includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Display alert and redirect to login
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Login Required',
            text: 'You need to be logged in to view your booking history.',
            confirmButtonText: 'Go to Login'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../login/log_in.php'; // Change this path to your actual login page
            }S
        });
    </script>
    ";
    exit;
}

// If user is logged in, get the user ID safely
$user_id = $_SESSION['user_id'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link href="../booking/booking style/booking_style.css" rel="stylesheet">

</head>

<body>


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
    <div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar" style="width: 250px; display: none;">
        <button class="close-btn btn btn-outline-danger mb-4" onclick="toggleSidebar()" style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>
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
                <form action="../../bws_ui/upload_profile.php" method="post" enctype="multipart/form-data" class="mt-3">
                    <div class="input-group mb-3">
                        <input type="file" name="profile_image" accept=".jpg, .jpeg, .png" class="form-control" id="inputGroupFile02">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Update Profile</button>
                </form>
                <a href="../logout.php" class="btn btn-danger btn-sm mt-3">Log Out</a>
            </div>
        <?php else: ?>
            <div class="user-interface text-center p-4 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
                <img src="../../bws_ui/images/user_profile/default_logo.jpg" alt="User Profile" class="profile-icon rounded-circle shadow-sm mb-2" style="width: 120px; height: 120px; border: 3px solid #6c757d; box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);">
                <h5 class="text-secondary mt-2">Guest</h5>
            </div>
        <?php endif; ?>

        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="../index.php" id="homeLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-home fa-lg me-3 icon"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../booking/booking.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-calendar-check fa-lg me-3 icon"></i>
                    <span>Booking</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../gallery.php" id="galleryLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-images fa-lg me-3 icon"></i>
                    <span>Gallery</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../services.php" id="servicesLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-spa fa-lg me-3 icon"></i>
                    <span>Services</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../../bws_ui/history/history.php" id="historyLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-history fa-lg me-3 icon"></i>
                    <span>History</span>
                </a>
            </li>

            <li class="mb- 3">
                <a href="#aboutus" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-info-circle fa-lg me-3 icon"></i>
                    <span>About Us</span>
                </a>
            </li>
        </ul>
    </div>
    <?php
    // Assuming the user ID is stored in the session as `user_id`
    $user_id = $_SESSION['user_id'];

    // Function to fetch bookings based on the table, status type, and user ID
    function fetchBookings($conn, $tableName, $status, $user_id)
    {
        if ($tableName == 'appointments') {
            $query = "
            SELECT a.appointment_date, a.appointment_time, a.payment_method, a.total_price, GROUP_CONCAT(bs.service_id) AS service_ids
            FROM appointments a
            LEFT JOIN booked_services bs ON a.id = bs.appointment_id
            WHERE a.status = '$status' AND a.user_id = '$user_id'
            GROUP BY a.id
        ";
        } elseif ($tableName == 'approved_bookings') {
            $query = "
            SELECT b.appointment_date, b.appointment_time, b.payment_method, b.total_price, ab.service_names
            FROM approved_bookings ab
            JOIN appointments b ON b.id = ab.appointment_id
            WHERE b.user_id = '$user_id'
        ";
        } elseif ($tableName == 'complete_bookings' || $tableName == 'no_show_bookings' || $tableName == 'cancelled_bookings') {
            $query = "
            SELECT b.appointment_date, b.appointment_time, b.payment_method, b.total_price, c.service_names
            FROM $tableName c
            JOIN appointments b ON b.id = c.appointment_id
            WHERE b.user_id = '$user_id'
        ";
        } else {
            $query = "SELECT * FROM $tableName";
        }

        $result = $conn->query($query);

        if ($result) {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $serviceNames = isset($row['service_names']) ? $row['service_names'] : $row['service_ids'];
                    echo '<div class="booking-card">';
                    echo '    <div class="card-header">';
                    echo '        <span class="date">' . htmlspecialchars($row['appointment_date']) . '</span>';
                    echo '        <span class="time">' . htmlspecialchars($row['appointment_time']) . '</span>';
                    echo '    </div>';
                    echo '    <div class="card-details">';
                    echo '        <p><strong>Service:</strong> ' . htmlspecialchars($serviceNames) . '</p>';
                    echo '        <p><strong>Price:</strong> ₱' . number_format($row['total_price'], 2) . '</p>';
                    echo '        <p><strong>Payment:</strong> ' . htmlspecialchars($row['payment_method']) . '</p>';
                    echo '    </div>';
                    echo '    <div class="status-label ' . strtolower($status) . '">' . ucfirst($status) . '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p class="no-booking-message">No ' . strtolower($status) . ' bookings found.</p>';
            }
        } else {
            echo "<p class='error-message'>Error executing query: " . $conn->error . "</p>";
        }
    }
    ?>

    <div class="container">
        <h1 class="title">Your Booking History</h1>

        <!-- Booking Tabs with Icon and Hover Effect -->
        <div class="booking-tabs">
            <button class="tab-button active" onclick="showSection('pending')">
                <i class="fas fa-clock"></i> Pending
            </button>
            <button class="tab-button" onclick="showSection('approved')">
                <i class="fas fa-check-circle"></i> Approved
            </button>
            <button class="tab-button" onclick="showSection('completed')">
                <i class="fas fa-check-double"></i> Completed
            </button>
            <button class="tab-button" onclick="showSection('no-show')">
                <i class="fas fa-times-circle"></i> No-Show
            </button>
            <button class="tab-button" onclick="showSection('canceled')">
                <i class="fas fa-ban"></i> Canceled
            </button>
        </div>

        <!-- Booking Sections -->
        <div id="pending" class="booking-section active-section">
            <h2 class="section-title pending">Pending Bookings</h2>
            <div class="card-container">
                <?php fetchBookings($conn, 'appointments', 'pending', $user_id); ?>
            </div>
        </div>

        <div id="approved" class="booking-section">
            <h2 class="section-title approved">Approved Bookings</h2>
            <div class="card-container">
                <?php fetchBookings($conn, 'approved_bookings', 'approved', $user_id); ?>
            </div>
        </div>

        <div id="completed" class="booking-section">
            <h2 class="section-title completed">Completed Bookings</h2>
            <div class="card-container">
                <?php fetchBookings($conn, 'complete_bookings', 'completed', $user_id); ?>
            </div>
        </div>

        <div id="no-show" class="booking-section">
            <h2 class="section-title no-show">No-Show Bookings</h2>
            <div class="card-container">
                <?php fetchBookings($conn, 'no_show_bookings', 'no-show', $user_id); ?>
            </div>
        </div>

        <div id="canceled" class="booking-section">
            <h2 class="section-title canceled">Canceled Bookings</h2>
            <div class="card-container">
                <?php fetchBookings($conn, 'cancelled_bookings', 'canceled', $user_id); ?>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            const sections = document.querySelectorAll('.booking-section');
            const buttons = document.querySelectorAll('.tab-button');

            sections.forEach(section => section.classList.remove('active-section'));
            buttons.forEach(button => button.classList.remove('active'));

            document.getElementById(sectionId).classList.add('active-section');
            document.querySelector(`[onclick="showSection('${sectionId}')"]`).classList.add('active');
        }

        document.addEventListener('DOMContentLoaded', () => {
            showSection('pending');
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../../bws_ui/booking/booking style/sw_services.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FontAwesome CDN for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../bws_ui/booking/process/sw_booking.js"></script>


    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.style.display = sidebar.style.display === 'block' ? 'none' : 'block';
        }
    </script>

    <script>
        document.getElementById('date').setAttribute('min', new Date().toISOString().split('T')[0]);
    </script>



</body>

</html>



<style>
    .booking-section {
        padding: 20px;
        margin-bottom: 20px;
        border: 2px solid #007bff;
        border-radius: 8px;
    }

    .section-label {
        font-size: 1.25rem;
        color: #007bff;
        font-weight: bold;
        margin-bottom: 10px;
    }

    #addedServices .card {
        border: 2px dashed #6c757d;
        padding: 15px;
    }

    .total-price-box {
        padding: 15px;
        border: 2px solid #28a745;
        border-radius: 8px;
        background-color: #f9fff9;
        text-align: right;
    }

    .font-weight-bold {
        font-size: 1.15rem;
    }


    .sidebar {
        position: fixed;
        /* Ensure it stays in place */
        left: 0;
        /* Align to the left */
        top: 0;
        /* Align to the top */
        height: 100%;
        /* Full height */
        overflow-y: auto;
        /* Scroll if necessary */
        transition: transform 0.3s ease;
        /* Smooth transition */
    }

    .selected-service-item {
        display: flex;
        align-items: center;
        padding: 10px;
        margin-top: 5px;
        border: 1px dotted #007bff;
        /* Dotted border */
        border-radius: 5px;
        /* Rounded corners */
        background-color: #f8f9fa;
        /* Light background for contrast */
    }

    .selected-service-item span {
        flex-grow: 1;
        /* Take available space */
    }






    .title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 30px;
        color: #444;
    }

    .booking-tabs {
        display: flex;
        justify-content: space-evenly;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .tab-button {
        background: linear-gradient(135deg, #FF9A8B 10%, #FF6A88 80%);
        color: white;
        padding: 12px 30px;
        border-radius: 50px;
        cursor: pointer;
        text-align: center;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin: 5px;
    }

    .tab-button:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2);
    }

    .tab-button.active {
        background: linear-gradient(135deg, #28a745 10%, #4CAF50 80%);
    }

    .tab-button i {
        font-size: 1.3rem;
    }

    .booking-section {
        display: none;
    }

    .booking-section.active-section {
        display: block;
    }

    .section-title {
        font-size: 2rem;
        text-align: center;
        margin-bottom: 20px;
        color: #444;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .booking-card {
        background-color: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        transform: perspective(800px) rotateY(0deg);
        transition: all 0.3s ease;
    }

    .booking-card:hover {
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        transform: scale(1.05) perspective(800px) rotateY(5deg);
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .card-details p {
        margin: 8px 0;
    }

    .status-label {
        text-align: center;
        padding: 8px 15px;
        font-weight: bold;
        border-radius: 20px;
        margin-top: 15px;
    }

    .pending {
        background-color: #ffca28;
        color: #fff;
    }

    .approved {
        background-color: #4caf50;
        color: #fff;
    }

    .completed {
        background-color: #9e9e9e;
        color: #fff;
    }

    .no-show {
        background-color: #f44336;
        color: #fff;
    }

    .canceled {
        background-color: #607d8b;
        color: #fff;
    }

    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .booking-tabs {
            flex-direction: column;
            align-items: center;
        }

        .tab-button {
            margin: 10px 0;
            width: 100%;
        }

        .card-container {
            grid-template-columns: 1fr;
        }
    }
</style>




<?php include '../../bws_ui/includes/footer.php' ?>