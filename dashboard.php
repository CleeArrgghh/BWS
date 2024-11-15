<?php
include_once '../bws_ui/includes/header.php';
include_once '../bws_ui/db_connection/db_connection.php'; // Include your DB connection



// Error Handling for Database Connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the count of registered users
$registeredUsersCount = countRegisteredUsers($conn);
$countServicesOffered = countServicesOffered($conn);
$countServiceCategories = countServiceCategories($conn);
$pendingBookingsCount = countPendingBookings($conn);
$countApprovedBookings = countApprovedBookings($conn);
$countCompletedBookings = countCompletedBookings($conn); // Add this line




// Fetch user data (e.g., number of users per month)
$query = "SELECT MONTH(created_at) AS month, COUNT(*) AS user_count FROM tbl_users GROUP BY MONTH(created_at)";
$result = $conn->query($query);

// Initialize arrays for labels and data
$months = [];
$user_counts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $months[] = date("F", mktime(0, 0, 0, $row['month'], 1));
        $user_counts[] = $row['user_count'];
    }
} else {
    $months = ['No Data'];
    $user_counts = [0];
}

// Close the database connection
$conn->close();
?>

<?php
// Function to count pending bookings
function countPendingBookings($conn)
{
    // Modify the query based on your table structure and column names
    $query = "SELECT COUNT(*) AS total FROM tbl_bookings WHERE status = 'Pending'";
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count of pending bookings
    } else {
        return 0; // Return 0 if the query fails
    }
}
?>
<!-- Navbar Section -->
<header class="navbar bg-light shadow-sm">
    <div class="container d-flex align-items-center">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle btn btn-outline-danger me-3" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Logo (Centered) -->
        <a href="dashboard.php" class="navbar-brand mx-auto text-dark">Bernadette Wellness Spa Admin Panel</a>
    </div>
</header>

    <!-- Sidebar Section -->
    <div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar">
        <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()" style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>
        <div class="text-center mb-3">
        <img src="./images/user_profile/admin.png" alt="Admin Image" class="img-fluid rounded-circle"
            style="width: 100px; height: 100px;">
            <h5 style="color: black;" class="mt-2">Admin</h5>
    </div>
        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="dashboard.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-tachometer-alt fa-lg me-3 icon"></i> <!-- Dashboard Icon -->
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-users fa-lg me-3 icon"></i> <!-- Registered Users Icon -->
                    <span>Registered Users</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="#" id="manageBookingsLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item shadow rounded p-3 bg-white" role="button" aria-expanded="false">
                    <i class="fas fa-calendar-alt fa-lg me-3 icon text-primary"></i>
                    <span class="fw-bold">Manage Bookings</span>
                    <i class="fas fa-chevron-down ms-auto toggle-icon text-secondary"></i>
                </a>
                <ul class="list-unstyled ms-4 mt-2 collapse" id="manageBookingsDropdown">
                    <li class="mb-2">
                        <a href="../booking/pending_booking.php" class="text-dark text-decoration-none d-flex align-items-center">
                            <i class="fas fa-clock me-2 text-warning"></i> Pending Bookings
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../booking/cancelled_booking_ui.php" class="text-dark text-decoration-none d-flex align-items-center">
                            <i class="fas fa-times-circle me-2 text-danger"></i> Cancelled Bookings
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../booking/no-show_ui.php" class="text-dark text-decoration-none d-flex align-items-center">
                            <i class="fas fa-user-slash me-2 text-muted"></i> No-Show Bookings
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../booking/approved_booking_ui.php" class="text-dark text-decoration-none d-flex align-items-center">
                            <i class="fas fa-check-circle me-2 text-success"></i> Approved Bookings
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../booking/sales.php" class="text-dark text-decoration-none d-flex align-items-center">
                            <i class="fas fa-dollar-sign me-2 text-info"></i> Sales
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="../booking/complete_bookings.php" class="text-dark text-decoration-none d-flex align-items-center">
                            <i class="fas fa-calendar-check me-2 text-primary"></i> Complete Bookings
                        </a>
                    </li>
                </ul>
            </li>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const manageBookingsLink = document.getElementById('manageBookingsLink');
                    const manageBookingsDropdown = document.getElementById('manageBookingsDropdown');
                    const toggleIcon = manageBookingsLink.querySelector('.toggle-icon');

                    manageBookingsLink.addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent default anchor click behavior

                        // Toggle the 'show' class to open/close the dropdown
                        const isExpanded = manageBookingsDropdown.classList.toggle('show');
                        manageBookingsLink.setAttribute('aria-expanded', isExpanded);

                        // Toggle the icon between chevron-down and chevron-up
                        toggleIcon.classList.toggle('fa-chevron-down', !isExpanded);
                        toggleIcon.classList.toggle('fa-chevron-up', isExpanded);
                    });
                });
            </script>


            <li class="mb-3">
                <a href="add_category.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-tags fa-lg me-3 icon"></i> <!-- Add Service Category Icon -->
                    <span>Add Service Category</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="add_services.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-plus-circle fa-lg me-3 icon"></i> <!-- Add Services Icon -->
                    <span>Add Services</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../bws_ui/reminders/reminders.php " class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-bell fa-lg me-3 icon"></i> <!-- Reminder Icon -->
                    <span>Appointment Reminders</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../bws_ui/staff/manage_staff.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-users fa-lg me-3 icon"></i> <!-- Staff Icon -->
                    <span>Manage Staff</span>
                </a>
            </li>

            <li style="text-align: center;">
            <h3 style="color: black;">Inventory</h3>
        </li>
        <li class="mb-3">
            <a href="add_product.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-plus-circle fa-lg me-3 icon"></i>
                <span>Add Items</span>
            </a>
        </li>
        <li style="text-align: center; padding: 10px;">
            <h5 style="color: #333; font-weight: 600; margin: 0; font-size: 1.2rem;">Discount Services</h5>
        </li>
        <li class="mb-3">
            <a href="discount.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-plus-circle fa-lg me-3 icon"></i>
                <span>Add Discount</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="#" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item"
                id="logoutLink">
                <i class="fas fa-sign-out-alt fa-lg me-3 icon"></i>
                <span>Log Out</span>
            </a>
        </li>
    </ul>
</div>


<!-- Main Dashboard Content -->
<div class="container mt-4">
    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow">
                <div class="card-header bg-purple text-white">
                    <h5 class="mb-0">Overall Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="stat-card p-4 bg-light text-center rounded shadow-sm transition-shadow hover-shadow">
                                <a href="../bws_ui/login/process/view_all_users.php" class="text-decoration-none text-primary">
                                    <h3 class="font-weight-bold"><?php echo $registeredUsersCount; ?></h3>
                                    <p class="font-weight-bold">Clients</p>
                                    <i class="fas fa-user fa-3x text-primary"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card p-4 bg-light text-center rounded shadow-sm transition-shadow hover-shadow">
                                <a href="../bws_ui/add_services.php" class="text-decoration-none text-primary">
                                    <h3 class="font-weight-bold"><?php echo $countServicesOffered; ?></h3>
                                    <p class="font-weight-bold">Services Offered</p>
                                    <i class="fas fa-concierge-bell fa-3x text-primary"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card p-4 bg-light text-center rounded shadow-sm transition-shadow hover-shadow">
                                <a href="../bws_ui/add_category.php" class="text-decoration-none text-primary">
                                    <h3 class="font-weight-bold"><?php echo $countServiceCategories; ?></h3>
                                    <p class="font-weight-bold">Service Categories</p>
                                    <i class="fas fa-th-list fa-3x text-primary"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card p-4 bg-light text-center rounded shadow-sm transition-shadow hover-shadow">
                                <a href="../bws_ui/booking/admin_approve_bookings.php" class="text-decoration-none text-danger">
                                    <h3 class="font-weight-bold"><?php echo $pendingBookingsCount; ?></h3>
                                    <p class="font-weight-bold">Pending Booking</p>
                                    <i class="fas fa-money-bill-wave fa-3x text-danger"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card p-4 bg-light text-center rounded shadow-sm transition-shadow hover-shadow">
                                <a href="../bws_ui/booking/admin_approve_bookings.php" class="text-decoration-none text-success">
                                    <h3 class="font-weight-bold"><?php echo $countApprovedBookings; ?></h3>
                                    <p class="font-weight-bold">Approved Bookings</p>
                                    <i class="fas fa-check-circle fa-3x text-success"></i> <!-- Use a different icon for approved bookings -->
                                </a>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="stat-card p-4 bg-light text-center rounded shadow-sm transition-shadow hover-shadow">
                                <a href="../bws_ui/booking/view_completed_bookings.php" class="text-decoration-none text-info">
                                    <h3 class="font-weight-bold"><?php echo $countCompletedBookings; ?></h3>
                                    <p class="font-weight-bold">Completed Bookings</p>
                                    <i class="fas fa-check-double fa-3x text-info"></i> <!-- Use a different icon for completed bookings -->
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom styles for hover effect */
    .hover-shadow {
        transition: box-shadow 0.3s ease;
    }

    .hover-shadow:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .transition-shadow {
        transition: all 0.3s ease;
    }
</style>


<?php
function countRegisteredUsers($conn)
{
    $query = "SELECT COUNT(*) AS total FROM tbl_users"; // Adjust the table name if different
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count
    } else {
        return 0; // Return 0 if the query fails
    }
}

function countServicesOffered($conn)
{
    $query = "SELECT COUNT(*) AS total FROM services"; // Adjust the table name if different
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count
    } else {
        return 0; // Return 0 if the query fails
    }
}


function countServiceCategories($conn)
{
    $query = "SELECT COUNT(*) AS total FROM service_categories"; // Adjust the table name if different
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count
    } else {
        return 0; // Return 0 if the query fails
    }
}



// Function to count approved bookings
function countApprovedBookings($conn)
{
    // Modify the query based on your table structure and column names
    $query = "SELECT COUNT(*) AS total FROM tbl_bookings WHERE status = 'Approved'"; // Ensure 'Approved' matches your status definition
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count of approved bookings
    } else {
        return 0; // Return 0 if the query fails
    }
}

// Function to count completed bookings
function countCompletedBookings($conn)
{
    // Modify the query based on your table structure and column names
    $query = "SELECT COUNT(*) AS total FROM tbl_complete_bookings"; // Adjust if needed
    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        return $row['total']; // Return the count of completed bookings
    } else {
        return 0; // Return 0 if the query fails
    }
}


?>




<?php include_once '../bws_ui/includes/footer.php'; ?>