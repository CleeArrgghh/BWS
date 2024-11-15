<?php
session_start();
include '../../bws_ui/db_connection/db_connection.php'; // Include your database connection
include '../../bws_ui/includes/header.php'; // Include your header file


?>
<?php
if (isset($_SESSION['success_message'])) {
    echo "<div class='alert alert-success text-center'>" . $_SESSION['success_message'] . "</div>";
    unset($_SESSION['success_message']);
}
if (isset($_SESSION['error_message'])) {
    echo "<div class='alert alert-danger text-center'>" . $_SESSION['error_message'] . "</div>";
    unset($_SESSION['error_message']);
}
?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">

</head>

<body>
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
        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="../dashboard.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-tachometer-alt fa-lg me-3 icon"></i> <!-- Dashboard Icon -->
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../bws_ui/login/process/view_all_users.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
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
            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item" id="logoutLink">
                    <i class="fas fa-sign-out-alt fa-lg me-3 icon"></i> <!-- Log Out Icon -->
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </div>


    <!-- Approved Bookings Section -->
    <div class="container mt-5">
        <div class="card shadow-lg border-0">
            <div class="card-body p-8">
                <h2 class="mb-4 text-center text-primary">Approved Bookings</h2>
                <table id="approvedBookingsTable" class="table table-striped table-hover table-bordered bookingTable" style="width:100%">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th>Full Name</th>
                            <th>Service Names</th>
                            <th>Appointment Date</th>
                            <th>Appointment Time</th>
                            <th>Payment Method</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch only approved bookings
                        $sql = "SELECT appointment_id, full_name, service_names, appointment_date, appointment_time, payment_method, total_price, 'approved' AS status FROM approved_bookings";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td><strong>" . htmlspecialchars($row['full_name']) . "</strong></td>";
                                echo "<td>" . htmlspecialchars($row['service_names']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['appointment_time']) . "</td>";
                                echo "<td>" . ucfirst(htmlspecialchars($row['payment_method'])) . "</td>";
                                echo "<td>₱" . number_format($row['total_price'], 2) . "</td>";
                                echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                                echo "<td>"
                                    . "<button type='button' class='btn btn-info edit-btn' "
                                    . "data-id='" . htmlspecialchars($row['appointment_id']) . "' "
                                    . "data-date='" . htmlspecialchars($row['appointment_date']) . "' "
                                    . "data-time='" . htmlspecialchars($row['appointment_time']) . "' title='Edit Time/Date'>"
                                    . "<i class='fas fa-edit'></i></button> "
                                    // Form for marking as No-Show
                                    . "<form method='post' action='../booking/process/no-show_process.php' class='d-inline'>"
                                    . "<input type='hidden' name='appointment_id' value='" . htmlspecialchars($row['appointment_id']) . "'>"
                                    . "<button type='submit' name='no_show' class='btn btn-warning' data-bs-toggle='tooltip' title='No-Show'><i class='fas fa-user-slash'></i></button>"
                                    . "</form> "
                                    // Cancel Booking Button
                                    . "<a href='../booking/process/cancel_booking_process.php?id=" . htmlspecialchars($row['appointment_id']) . "' class='btn btn-danger' data-bs-toggle='tooltip' title='Cancel'><i class='fas fa-times'></i></a> "
                                    // Complete Booking Button
                                    . "<a href='../booking/process/complete_bookings_process.php?id=" . htmlspecialchars($row['appointment_id']) . "' class='btn btn-primary' data-bs-toggle='tooltip' title='Complete'><i class='fas fa-check-circle'></i></a> "
                                    . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8' class='text-center'>No approved bookings found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-top modal-custom-width" style="max-width: 450px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Appointment Date and Time</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="post" action="process/process_edit_time_date.php">
                <input type="hidden" name="appointment_id" id="appointment_id">
                <div class="modal-body modal-body-custom-height">
                    <div class="mb-3">
                        <label for="appointment_date" class="form-label">Appointment Date</label>
                        <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointment_time" class="form-label">Appointment Time</label>
                        <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <?php $conn->close(); ?>

    <!-- Include jQuery and DataTables JavaScript library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        $(document).ready(function() {
            $('#approvedBookingsTable').DataTable({
                responsive: true,
                "autoWidth": false,
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });



        });
    </script>
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
    <script>
    $(document).on('click', '.edit-btn', function() {
        // Retrieve data attributes from the clicked button
        const appointmentId = $(this).data('id');
        const appointmentDate = $(this).data('date');
        const appointmentTime = $(this).data('time');
        
        // Set the values in the modal fields
        $('#appointment_id').val(appointmentId);
        $('#appointment_date').val(appointmentDate);
        $('#appointment_time').val(appointmentTime);
        
        // Show the modal
        $('#editModal').modal('show');
    });
</script>

</body>

</html>

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

        .status {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            /* Space between text and dot */
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
        /* Set a minimum height as per your layout needs */
    }
</style>


<?php include '../../bws_ui/includes/footer.php' ?>