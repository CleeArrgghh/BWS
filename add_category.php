<?php include_once '../bws_ui/includes/header.php'; ?>

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

<!-- Main Content Section -->
<div class="container my-4">
    <div class="row">
        <div class="col-md-12 mb-4 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Add Service Category
            </button>
        </div>

        <div class="col-md-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center mb-4">Manage Service Categories</h2>
                    <div class="table-wrapper">
                        <table id="categoriesTable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include_once '../bws_ui/db_connection/db_connection.php';
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }
                                $query = "SELECT id, name, description, image FROM service_categories";
                                $result = mysqli_query($conn, $query);
                                if (!$result) {
                                    die("Query failed: " . mysqli_error($conn));
                                }
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                    echo '<td><img src="../bws_ui/images/' . htmlspecialchars($row['image']) . '" alt="Category Image" class="img-fluid" style="max-width: 100px;"></td>';
                                    echo '<td>
                                            <button class="btn btn-warning btn-sm me-2" onclick="openEditModal(' . $row['id'] . ', \'' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($row['image'], ENT_QUOTES, 'UTF-8') . '\')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="delete_service_category.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this category?\')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>';
                                    echo '</tr>';
                                }
                                mysqli_close($conn);
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add Service Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_category_process.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="categoryId" name="categoryId" value="">
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" id="categoryName" name="categoryName"
                                placeholder="Enter category name" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="categoryDescription" name="categoryDescription" rows="3"
                                placeholder="Enter description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="categoryImage" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="categoryImage" name="categoryImage"
                                accept="image/*">
                        </div>
                        <button type="submit" id="submitButton" class="btn btn-primary w-100">Add Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit Service Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_category_process.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="editCategoryId" name="categoryId" value="">
                        <div class="mb-3 input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control" id="editCategoryName" name="categoryName"
                                placeholder="Enter category name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editCategoryDescription" name="categoryDescription"
                                rows="3" placeholder="Enter description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editCategoryImage" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="editCategoryImage" name="categoryImage"
                                accept="image/*">
                        </div>
                        <img id="editImagePreview" src="" alt="Current Category Image"
                            style="max-width: 100px; display: none;" />
                        <button type="submit" id="editSubmitButton" class="btn btn-primary w-100">Update
                            Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="text-center bg-light-purple footer-spacing">
        <p class="mb-0">&copy; 2024 Bernadette Wellness Spa. All rights reserved.</p>
    </footer>
</div>

<!-- DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables Initialization Script -->
<script>
    $(document).ready(function () {
        $('#categoriesTable').DataTable({
            responsive: true,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50, 100]
        });
    });

    function openEditModal(id, name, description, image) {
        $('#editCategoryId').val(id);
        $('#editCategoryName').val(name);
        $('#editCategoryDescription').val(description);
        $('#editImagePreview').attr('src', '../bws_ui/images/' + image).show();

        // Show the edit modal
        $('#editCategoryModal').modal('show');
    }
</script>

<!-- Inline JavaScript for Sidebar Toggle -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }
</script>

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
        /* Set a minimum height as per your layout needs */
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