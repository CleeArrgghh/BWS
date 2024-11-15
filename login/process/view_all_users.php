<?php
// Include database connection
include '../../db_connection/db_connection.php';
include '../../includes/header.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!-- Navbar Section -->
<header class="navbar" shadow-sm>
    <div class="container d-flex align-items-center justify-content-between">
        <!-- Sidebar Toggle Button -->
        <button class="sidebar-toggle btn btn-outline-danger me-3" onclick="toggleSidebar()"
            aria-label="Toggle Sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar Brand -->
        <a href="add_category.php" class="navbar-brand text-dark">Bernadette Wellness Spa Admin Panel</a>

        <!-- Date and Time Display -->
        <div class="ms-auto">
            <span id="currentDateTime" class="text-dark"></span>
        </div>
    </div>
</header>

<!-- Sidebar Section -->
<div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar" style="width: 250px;">
    <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()"
        style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>

    <!-- Admin Image Section -->
    <div class="text-center mb-3">
        <img src="./images/user_profile/admin1.jpg" alt="Admin Image" class="img-fluid rounded-circle"
            style="width: 100px; height: 100px;">
            <h5 style="color: black;" class="mt-2">Admin</h5>
    </div>
    <ul class="list-unstyled">
        <li class="mb-3">
            <a href="dashboard.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-tachometer-alt fa-lg me-3 icon"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="../../bws_ui/login/process/view_all_users.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-users fa-lg me-3 icon"></i> <!-- Registered Users Icon -->
                <span>Registered Users</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="../bws_ui/booking/admin_approve_bookings.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-calendar-alt fa-lg me-3 icon"></i>
                <span>Manage Bookings</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="add_category.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-plus-circle fa-lg me-3 icon"></i>
                <span>Add Service Category</span>
            </a>
        </li>
        <li class="mb-3">
            <a href="add_services.php"
                class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-plus-circle fa-lg me-3 icon"></i>
                <span>Add Services</span>
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Data</title>

    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../../login/login_style/table_style.css">

    <style>
    .modal-backdrop {
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent black */
    }

    /* Adjust Modal Dialog Width */
    .modal-dialog {
        max-width: 500px;
        /* Reduces the modal width */
        margin: 1.75rem auto;
    }

    /* Reduce Modal Body Padding */
    .modal-body {
        padding: 1.5rem;
        /* Adjust padding for a more compact look */
    }

    /* Keep Modal Content Style */
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Modal Header and Button Styles */
    .modal-header {
        background-color: #6a1b9a;
        color: #ffffff;
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
    }

    .modal-title {
        font-weight: 600;
    }

    /* Form Controls and Buttons */
    .input-group-text,
    .form-label {
        color: black;
        border: none;
    }

    .form-control {
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #6a1b9a;
    }

    .btn-primary {
        background-color: #6a1b9a;
        border: none;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-primary:hover {
        background-color: #8e24aa;
    }

    .table-wrapper {
        max-height: 400px;
        overflow-y: auto;
    }

    .footer-spacing {
        padding: 1rem;
        background-color: #f8f9fa;
    }

    .bg-light-purple {
        background-color: #f0eaff;
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        height: 100%;
        background-color: #f8f9fa;
        transition: left 0.3s ease;
    }

    .sidebar.open {
        left: 0;
    }

    .footer-spacing {
        padding: 1rem;
        background-color: #f8f9fa;
    }

    .bg-light-purple {
        background-color: #f0eaff;
    }

    .card-fixed-height {
        min-height: 500px;
    }
</style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">User Data</h3>
                <div class="table-responsive">
                    <?php
                    // Fetch data from tbl_users
                    $sql = "SELECT user_id, username, firstname, middlename, lastname, phone, address, age, dob, gender, created_at FROM tbl_users";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo "<table id='usersTable' class='table table-striped table-bordered'>
                        <thead>
                        <tr>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Last Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Age</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>";

                        // Output data for each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td>" . $row['username'] . "</td>
                                <td>" . $row['firstname'] . "</td>
                                <td>" . $row['middlename'] . "</td>
                                <td>" . $row['lastname'] . "</td>
                                <td>" . $row['phone'] . "</td>
                                <td>" . $row['address'] . "</td>
                                <td>" . $row['age'] . "</td>
                                <td>" . $row['dob'] . "</td>
                                <td>" . $row['gender'] . "</td>
                                <td>" . $row['created_at'] . "</td>
                                <td class='text-center'>
                                    <a href='delete_user.php?id=" . $row['user_id'] . "' class='btn btn-sm btn-danger me-2' title='Delete' onclick=\"return confirm('Are you sure you want to delete this user?');\">
                                        <i class='fas fa-trash'></i>
                                    </a>
                                </td>
                            </tr>";
                        }
                        echo "</tbody></table>";
                    } else {
                        echo "<div class='alert alert-warning' role='alert'>No users found.</div>";
                    }

                    // Close the database connection
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- Include DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

    <script>
        // Initialize DataTable
        $(document).ready(function () {
            $('#usersTable').DataTable();
        });
    </script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
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

</body>

</html>
<?php include "../../includes/footer.php"; ?>