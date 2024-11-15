<?php
session_start(); // Start the session
include "../bws_ui/db_connection/db_connection.php";




// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Display a SweetAlert message and redirect to login page
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Redirecting...</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <script>
            Swal.fire({
                icon: "warning",
                title: "Please log in",
                text: "You need to log in to view your booking history.",
                confirmButtonText: "Log In",
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../bws_ui/login/log_in.php";
                }
            });
        </script>
    </body>
    </html>';
    exit; // Stop further execution
}


$conn->close();
?>






<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400&display=swap" rel="stylesheet">
    <link href="../../bws_ui/history/history_style/history_style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

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
                <form action="../../upload_profile.php" method="post" enctype="multipart/form-data" class="mt-3">
                    <div class="input-group mb-3">
                        <input type="file" name="profile_image" accept=".jpg, .jpeg, .png" class="form-control" id="inputGroupFile02">

                    </div>
                    <button type="submit" class="btn btn-primary btn-sm">Update Profile</button>
                </form>
                <a href="logout.php" class="btn btn-danger btn-sm mt-3">Log Out</a>
            </div>
        <?php else: ?>
            <div class="user-interface text-center p-4 mb-4 rounded shadow-sm" style="background-color: #f8f9fa;">
                <img src="../images/user_profile/default_logo.jpg" alt="User Profile" class="profile-icon rounded-circle shadow-sm mb-2" style="width: 120px; height: 120px; border: 3px solid #6c757d; box-shadow: 0 0 15px rgba(0, 0, 0, 0.15);">
                <h5 class="text-secondary mt-2">Guest</h5>
            </div>
        <?php endif; ?>




        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="../bws_ui/index.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-home fa-lg me-3 icon"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../bws_ui/booking/booking.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
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
                <a href="services.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-spa fa-lg me-3 icon"></i>
                    <span>Services</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-history fa-lg me-3 icon"></i> <!-- Updated icon -->
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

    <div class="container mt-5">

        <!-- Card containing messages and the Complete Bookings Table -->
        <div class="card mb-4 shadow">
            <div class="card-body">
                <!-- Display success message if set in the session -->
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success']; ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- Display error message if set in the session -->
                <?php if (isset($_SESSION['error'])) : ?>
                    <div class="alert alert-danger">
                        <?= $_SESSION['error']; ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Display username -->
                <div class="alert alert-info">
                    Logged in as: <?= htmlspecialchars($_SESSION['username']); ?>
                </div>

                <h2 class="text-center mb-4"><b>Booking History</b></h2>

                <table id="complete-bookings" class="table table-striped table-bordered">
                    <thead class="thead-light">
                        <tr>
                            <th>Service Name</th>
                            <th>Price</th>
                            <th>Booking Date</th>
                            <th>Booking Time</th>
                            <th>Phone</th>
                            <th>Username</th>
                            <th>Status</th> <!-- New column for status -->
                        </tr>
                    </thead>
                    <tbody>

                            <tr>
                                <td><?= htmlspecialchars($booking['service_name']); ?></td>
                                <td><?= htmlspecialchars($booking['price']); ?></td>
                                <td><?= htmlspecialchars($booking['booking_date']); ?></td>
                                <td><?= htmlspecialchars($booking['booking_time']); ?></td>
                                <td><?= htmlspecialchars($booking['phone']); ?></td>
                                <td><?= htmlspecialchars($booking['username']); ?></td>
                                <td>
                                    <?php if ($booking['status'] == 'paid') : ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php elseif ($booking['status'] == 'ingoing') : ?>
                                        <span class="badge badge-warning">Ingoing</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Unknown</span>
                                    <?php endif; ?>
                                </td> <!-- New column for status -->
                            </tr>
                    
                    </tbody>
                </table>
            </div>
        </div>

    </div>






    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../bws_ui/resources/bootstrap/js/toggle.js"></script>
    <script>
        $(document).ready(function() {
            $('#complete-bookings').DataTable();
        });
    </script>

</body>

</html>

<?php include "../bws_ui/includes/footer.php"; ?>



<style>
    body {
        font-family: 'Cinzel', serif;
        background-color: #f9f7f5;
        /* Light background */
        color: #333;
        /* Dark text for contrast */
    }

    .badge-success {
        background-color: #2ecc71;
    }

    .badge-warning {
        background-color: #f1c40f;
    }

    .badge-danger {
        background-color: #e74c3c;
    }

    .card {
        background-color: #fff8f0;
        /* Light parchment color */
        border: 1px solid #d1cfcf;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-weight: bold;
        margin-bottom: 20px;
    }



    body {
        margin: 0;
        font-family: Arial, sans-serif;
        color: var(--text-color-dark);
        background: linear-gradient(to right, var(--secondary-color), #ffffff);
        overflow-x: hidden;
    }

    /* Navbar Sidebar Styles */
    .navbar {
        position: sticky;
        top: 0;
        z-index: 1000;
        background-color: #f8f9fa;
        box-shadow: 0 2px 4px var(--shadow);
        display: flex;
        justify-content: space-between;
    }

    .navbar .container {
        display: flex;
        width: 100%;
        max-width: 1500px;
    }

    .navbar .navbar-brand {
        flex-grow: 1;
        /* Center the brand logo */
        text-align: center;
        /* Center the text */
    }

    .sidebar {
        background-color: #f8f9fa;
        /* Light background for the sidebar */
        width: 250px;
        /* Fixed width for the sidebar */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        /* Subtle shadow for depth */
    }

    .sidebar-toggle {
        display: block;
        font-size: 1.5em;
        background: none;
        border: none;
        cursor: pointer;
        color: var(--primary-color);
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background: var(--primary-color);
        color: var(--text-color-light);
        box-shadow: 2px 0 5px var(--shadow);
        transition: left 0.3s ease;
        padding: 20px;
        overflow-y: auto;
        z-index: 1000;
    }

    .sidebar ul {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom: 20px;
    }

    .sidebar ul li a {
        color: var(--text-color-light);
        text-decoration: none;
        font-size: 1.2em;
        display: block;
        transition: color 0.3s ease;
    }

    .sidebar ul li a:hover {
        color: var(--hover-color);
    }

    .close-btn {
        background: none;
        border: none;
        color: var(--text-color-light);
        font-size: 2em;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .icon-hover:hover {
        background-color: rgba(255, 0, 0, 0.1);
        /* Light red background on hover */
        border-radius: 4px;
        /* Rounded corners on hover */
        transition: background-color 0.3s ease;
        /* Smooth transition */
    }


    /* List Item Styles */
    .list-item {
        padding: 10px 15px;
        /* Padding for list items */
        background-color: white;
        /* Background color for each item */
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Initial shadow for depth */
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        /* Smooth transitions */
    }

    .list-item:hover {
        transform: translateY(-5px) rotateY(5deg);
        /* Lift and rotate on hover */
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        /* Increase shadow on hover for floating effect */
    }

    /* Button Styles */
    .close-btn {
        color: #6f42c1;
        /* Purple color for close button */
    }

    .btn-outline-purple {
        color: purple;
        /* Text color */
        border-color: purple;
        /* Border color */
    }

    .btn-outline-purple:hover {
        background-color: purple;
        /* Background color on hover */
        color: white;
        /* Text color on hover */
        transition: background-color 0.3s ease;
        /* Smooth transition */
    }

    /* User Interface Section */
    .user-interface {
        padding: 10px;
        /* Padding around the user interface */
        background-color: rgba(255, 255, 255, 0.8);
        /* Slightly transparent background */
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        /* Subtle shadow */
    }
</style>