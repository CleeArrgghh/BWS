<?php
// Start the session
session_start();

include "../../bws_ui/includes/header.php";
include "../../bws_ui/db_connection/db_connection.php"

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff & Positions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="../../bws_ui/staff/staff_styles/staff_style.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

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
            <a href="../../bws_ui/dashboard.php" class="navbar-brand mx-auto text-dark">Bernadette Wellness Spa Admin Panel</a>
        </div>
    </header>
    <!-- Sidebar Section -->
    <div class="sidebar bg-light shadow-lg p-3 mb-5 rounded" id="sidebar" style="width: 250px;">
        <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()" style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>


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
                <a href="../bws_ui/booking/admin_approve_bookings.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-calendar-alt fa-lg me-3 icon"></i> <!-- Manage Bookings Icon -->
                    <span>Manage Bookings</span>
                </a>
            </li>

            <li class="mb-3">
                <a href="../add_category.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-tags fa-lg me-3 icon"></i> <!-- Add Service Category Icon -->
                    <span>Add Service Category</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="../add_services.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
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
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-users fa-lg me-3 icon"></i> <!-- Staff Icon -->
                    <span>Manage Staff</span>
                </a>
            </li>

            <li class="mb-3">
                <a href="../../bws_ui/logout.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item" id="logoutLink">
                    <i class="fas fa-sign-out-alt fa-lg me-3 icon"></i> <!-- Log Out Icon -->
                    <span>Log Out</span>
                </a>
            </li>


        </ul>
    </div>

        <?php
            $staffQuery = "SELECT s.staff_id, s.firstname, s.middlename, s.lastname, s.phone, s.address, s.dob, s.gender, s.position_id, p.position_name, s.image_path, s.status
            FROM tbl_staff s
            JOIN tbl_positions p ON s.position_id = p.position_id";
            $staffResult = $conn->query($staffQuery);
        ?>
        <div class="container mt-5">
            <!-- Heading in Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h1 class="mb-4 text-center">Manage Staff & Positions</h1>
                </div>
            </div>

            <!-- Staff List Card -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Staff List
                        <button type="button" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                            <i class="fas fa-user-plus"></i> Add New Staff
                        </button>
                    </h4>
                </div>
                <!-- Staff Table -->
                <div class="table-responsive">
                    <table id="staffTable" class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Gender</th>
                                <th>Date of Birth</th>
                                <th>Position</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Image</th> <!-- Moved Image column here -->
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($staffResult->num_rows > 0): ?>
                                <?php while ($row = $staffResult->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['firstname'] . ' ' . ($row['middlename'] ? $row['middlename'] . ' ' : '') . $row['lastname']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo $row['gender']; ?></td>
                                        <td><?php echo $row['dob']; ?></td> 
                                        <td><?php echo $row['position_name']; ?></td>
                                        <td><?php echo $row['phone']; ?></td>
                                        <td>
                                            <span class="status-dot <?php echo ($row['status'] === 'Available') ? 'status-available' : 'status-not-available'; ?>"></span>
                                            <?php echo $row['status']; ?>
                                        </td>
                                        <td>
                                            <img src="<?php echo !empty($row['image_path']) ? '../staff_images/' . $row['image_path'] : '../staff_images'; ?>" 
                                                alt="Staff Image" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-warning btn-sm me-2"
                                                data-bs-toggle="modal" data-bs-target="#editStaffModal"
                                                onclick="openEditModal('<?php echo $row['staff_id']; ?>', '<?php echo addslashes($row['firstname']); ?>', '<?php echo addslashes($row['middlename']); ?>', '<?php echo addslashes($row['lastname']); ?>', '<?php echo $row['dob']; ?>', '<?php echo addslashes($row['address']); ?>', '<?php echo $row['gender']; ?>', '<?php echo $row['position_id']; ?>', '<?php echo $row['phone']; ?>')">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            <a href="../staff/process/delete_staff.php?id=<?php echo $row['staff_id']; ?>" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No staff found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <!-- Add Staff Modal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStaffModalLabel">Add New Staff</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStaffForm" action="../staff/process/add_staff.php" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstname" id="firstName" placeholder="Enter First Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="middleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" id="middleName" placeholder="Enter Middle Name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastname" id="lastName" placeholder="Enter Last Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" id="dob" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter Address" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="staffStatus" class="form-label">Status</label>
                                <select class="form-control" name="status" id="staffStatus" required>
                                    <option value="Available">Available</option>
                                    <option value="Not Available">Not Available</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-control" name="gender" id="gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <?php
                                // Fetch positions from database
                                $sql = "SELECT position_id, position_name FROM tbl_positions";
                                $result = $conn->query($sql);
                                $positions = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $positions[] = $row;
                                    }
                                }
                                ?>
                                <label for="staffPosition" class="form-label">Position</label>
                                <select class="form-control" name="staffPosition" id="staffPosition" required>
                                    <option value="">Select a Position</option>
                                    <?php foreach ($positions as $position): ?>
                                        <option value="<?php echo $position['position_id']; ?>">
                                            <?php echo $position['position_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="staffImage" class="form-label">Staff Image</label>
                                <input type="file" class="form-control" name="staffImage" id="staffImage" accept="image/*" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input type="tel" class="form-control" name="phone" id="phone" maxlength="10" required oninput="validatePhoneInput(this);">
                                </div>
                            </div>
                            <script>
                                function validatePhoneInput(input) {
                                    const value = input.value;
                                    if (value.length === 1 && value === '0') {
                                        input.value = '';
                                    }
                                    if (value.length > 10) {
                                        input.value = value.slice(0, 10);
                                    }
                                }
                            </script>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="addStaffForm" class="btn btn-primary">Add Staff</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Staff Modal -->
    <div class="modal fade" id="editStaffModal" tabindex="-1" aria-labelledby="editStaffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStaffModalLabel">Edit Staff Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editStaffForm" action="../../bws_ui/staff/process/edit_staff.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="staff_id" id="editStaffId">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editFirstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" name="firstname" id="editFirstName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editMiddleName" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" id="editMiddleName">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editLastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" name="lastname" id="editLastName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editDob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="dob" id="editDob" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="editAddress" class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="editAddress" rows="3" required></textarea>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="staffStatus" class="form-label">Status</label>
                                <select class="form-control" name="status" id="staffStatus" required>
                                    <option value="Available">Available</option>
                                    <option value="Not Available">Not Available</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editGender" class="form-label">Gender</label>
                                <select class="form-control" name="gender" id="editGender" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editPosition" class="form-label">Position</label>
                                <select class="form-control" name="staffPosition" id="staffPosition" required>
                                    <option value="">Select a Position</option>
                                    <?php foreach ($positions as $position): ?>
                                        <option value="<?php echo $position['position_id']; ?>">
                                            <?php echo $position['position_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editStaffImage" class="form-label">Staff Image</label>
                                <input type="file" class="form-control" name="staffImage" id="editStaffImage" accept="image/*">
                            </div>
                            <div class="form-group mb-3">
                                <label for="editPhone"><i class="fas fa-phone"></i> Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input type="tel" class="form-control" name="phone" id="editPhone" maxlength="10" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Update Staff</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
        <div class="container mt-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Position List
                        <a href="#" class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#addPositionModal">
                            <i class="fas fa-plus"></i> Add New Position
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="positionTable" class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Position</th>
                                    <th>Description</th> 
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM tbl_positions";
                                $result = mysqli_query($conn, $query);

                                if (!$result) {
                                    die("Query failed: " . mysqli_error($conn));
                                }

                                if (mysqli_num_rows($result) === 0) {
                                    echo "<tr><td colspan='4'>No positions found.</td></tr>"; // Updated colspan to 4
                                } else {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<tr>
                                        <td>{$row['position_name']}</td>
                                        <td>{$row['description']}</td> <!-- Displaying Description -->
                                        <td>
                                            <a href='#' class='btn btn-warning btn-sm me-2' data-bs-toggle='modal' data-bs-target='#editPositionModal'
                                            onclick=\"setEditPosition('{$row['position_id']}', '{$row['position_name']}', '{$row['description']}')\">
                                                <i class='fas fa-edit'></i> Edit
                                            </a>
                                        <a href='../staff/process/delete_position.php?id={$row['position_id']}' class='btn btn-danger btn-sm'>
                                                <i class='fas fa-trash'></i> Delete
                                            </a>

                                        </td>
                                    </tr>";
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Position Modal -->
        <div class="modal fade" id="addPositionModal" tabindex="-1" aria-labelledby="addPositionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addPositionModalLabel">Add New Position</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addPositionForm" action="../../bws_ui/staff/process/add_position.php" method="POST">
                            <div class="mb-3">
                                <label for="positionName" class="form-label">Position Name</label>
                                <input type="text" class="form-control" id="positionName" name="positionName" required>
                            </div>
                            <div class="mb-3">
                                <label for="positionDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="positionDescription" name="positionDescription" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-custom">Add Position</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Position Modal -->
        <div class="modal fade" id="editPositionModal" tabindex="-1" aria-labelledby="editPositionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPositionModalLabel">Edit Position</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editPositionForm" action="../staff/process/edit_position.php" method="POST">
                            <input type="hidden" id="editPositionId" name="position_id">
                            <div class="mb-3">
                                <label for="editPositionName" class="form-label">Position Name</label>
                                <input type="text" class="form-control" id="editPositionName" name="positionName" required>
                            </div>
                            <div class="mb-3">
                                <label for="editPositionDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editPositionDescription" name="positionDescription" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-update">Update Position</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openEditModal(staff_id, firstname, middlename, lastname, dob, address, gender, position_id, phone, status) {
                document.getElementById('editStaffId').value = staff_id;
                document.getElementById('editFirstName').value = firstname;
                document.getElementById('editMiddleName').value = middlename;
                document.getElementById('editLastName').value = lastname;
                document.getElementById('editDob').value = dob;
                document.getElementById('editAddress').value = address;
                document.getElementById('editGender').value = gender;
                document.getElementById('editPosition').value = position_id;
                document.getElementById('editPhone').value = phone; // Auto-fill phone number
                document.getElementById('editStaffImagePreview').src = image_url || '../staff/staff_images/default-image.jpg';
            }
        </script>
        <script>
            function setEditPosition(positionId, positionName, positionDescription) {
                document.getElementById('editPositionId').value = positionId;
                document.getElementById('editPositionName').value = positionName;
                document.getElementById('editPositionDescription').value = positionDescription;
            }
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="../../bws_ui/resources/bootstrap/js/toggle.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).ready(function() {
                $('#staffTable').DataTable();
                $('#positionTable').DataTable();
            });
        </script>

        <script>
            function preventModalClose(event) {
                event.preventDefault(); // Prevent the default close action
            }
        </script>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    table thead th {
    background-color: #6a1b9a !important;
    color: #ffffff;
    }
    .btn-update {
        background-color: #6a1b9a;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-update:hover {
        background-color: #8e24aa;
    }
</style>
<?php include "../../bws_ui/includes/footer.php"; ?>