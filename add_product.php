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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Product</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <style>
        h1 {
            text-align: center;
            color: #333;
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-dialog {
            max-width: 600px;
            margin: 2rem auto;
        }

        .modal-header {
            background-color: #6a1b9a !important;
            color: #ffffff;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 1rem;
        }

        .modal-title {
            font-weight: 600;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #6a1b9a;
        }

        .modal-header {
            background-color: #f8f9fa;
            /* Light gray background */
            border-bottom: 1px solid #dee2e6;
            /* Border for separation */
        }

        .modal-title {
            color: #343a40;
            /* Darker title color */
        }

        .modal-body {
            padding: 20px;
            /* Increased padding for body */
        }

        .form-label {
            font-weight: bold;
            /* Make labels bold */
        }

        .form-control {
            border-radius: 0.5rem;
            /* Rounded borders */
        }

        .modal-footer {
            justify-content: space-between;
            /* Space between buttons */
        }

        .btn-primary,
        .btn-secondary {
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #6a1b9a;
            border: none;
            display: flex;
            justify-content: center;
            width: 60%;
        }

        .btn-primary:hover {
            background-color: #8e24aa;
        }

        .table-wrapper {
            max-height: 400px;
            overflow-y: auto;
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

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .status-available .status-dot {
            background-color: green;
        }

        .status-low-stock .status-dot {
            background-color: yellow;
        }

        .status-out-of-stock .status-dot {
            background-color: red;
        }

        td {
            padding: 8px 10px;
            font: 16px Arial, sans-serif;
            box-sizing: border-box;
        }

        .alert {
            color: #333;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: bold;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .alert i {
            margin-right: 10px;
            font-size: 1.5rem;
        }

        .alert button {
            background: none;
            border: none;
            color: #333;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .alert-low-stock {
            background-color: #ffc107;
        }

        .alert-available {
            background-color: #28a745;
            color: #fff;
        }

        .alert-out-of-stock {
            background-color: #dc3545;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="container my-4">
        <div class="row">
            <div class="col-md-12 mb-4 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus"></i> Add Product
                </button>
            </div>
            <?php
            include_once '../bws_ui/db_connection/db_connection.php';
            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch products along with categories
            $query = "SELECT p.id, p.name, p.description, p.quantity, sc.name AS category, p.image 
          FROM products p 
          JOIN service_categories sc ON p.category_id = sc.id";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $lowStockProducts = [];
            $availableProducts = [];
            $outOfStockProducts = [];
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['quantity'] >= 6) {
                    $availableProducts[] = htmlspecialchars($row['name']);
                } elseif ($row['quantity'] > 0 && $row['quantity'] < 6) {
                    $lowStockProducts[] = htmlspecialchars($row['name']);
                } else {
                    $outOfStockProducts[] = htmlspecialchars($row['name']);
                }
            }

            if (!empty($availableProducts)) {
                echo '<div class="alert alert-available"><i class="fas fa-check-circle"></i> Available Products: ' . implode(', ', $availableProducts) . '<button onclick="this.parentElement.style.display=\'none\';">&times;</button></div>';
            }
            if (!empty($lowStockProducts)) {
                echo '<div class="alert alert-low-stock"><i class="fas fa-exclamation-triangle"></i> Low Stock Alert: ' . implode(', ', $lowStockProducts) . '<button onclick="this.parentElement.style.display=\'none\';">&times;</button></div>';
            }
            if (!empty($outOfStockProducts)) {
                echo '<div class="alert alert-out-of-stock"><i class="fas fa-times-circle"></i> Out of Stock Products: ' . implode(', ', $outOfStockProducts) . '<button onclick="this.parentElement.style.display=\'none\';">&times;</button></div>';
            }

            // Reset result pointer to loop through again
            mysqli_data_seek($result, 0);
            ?>
            <script>
                // Automatically hide the alerts after 10 seconds
                setTimeout(function () {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        alert.style.transition = 'opacity 1s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 1000); // Remove the element after fade-out
                    });
                }, 10000); // 10 seconds
            </script>
            <div class="col-md-12 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Manage Products</h2>
                        <div class="table-wrapper">
                            <table id="productTable" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Image</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $quantity = $row['quantity'];
                                        $statusClass = '';
                                        $productStatus = '';

                                        if ($quantity >= 6) {
                                            $statusClass = 'status-available';
                                            $productStatus = 'Available';
                                        } elseif ($quantity > 0 && $quantity < 6) {
                                            $statusClass = 'status-low-stock';
                                            $productStatus = 'Low Stock';
                                        } else {
                                            $statusClass = 'status-out-of-stock';
                                            $productStatus = 'Out of Stock';
                                        }

                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['description']) . '</td>';
                                        echo '<td>' . htmlspecialchars($quantity) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['category']) . '</td>';
                                        echo '<td><span class="' . $statusClass . ' status"><span class="status-dot"></span>' . htmlspecialchars($productStatus) . '</span></td>';  // Updated status column
                                        echo '<td><img src="../bws_ui/images/' . htmlspecialchars($row['image']) . '" alt="Product Image" class="img-fluid" style="max-width: 100px;"></td>';
                                        echo '<td>
                                                <button class="btn btn-warning btn-sm me-2" onclick="openEditModal(' . $row['id'] . ', \'' . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($row['description'], ENT_QUOTES, 'UTF-8') . '\', ' . $quantity . ', \'' . htmlspecialchars($row['category'], ENT_QUOTES, 'UTF-8') . '\')">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="delete_product.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Are you sure you want to delete this product?\')">
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
    </div>
        <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addProductForm" action="add_product_process.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="productQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="productQuantity" name="productQuantity" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="serviceCategory" class="form-label">Category</label>
                            <select class="form-select" id="serviceCategory" name="serviceCategory" required>
                                <option value="" disabled selected>Select a category</option>
                                <?php
                                // Fetch categories from database
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                $categoryQuery = "SELECT id, name FROM service_categories";
                                $categoryResult = mysqli_query($conn, $categoryQuery);
                                while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                                    echo '<option value="' . $categoryRow['id'] . '">' . htmlspecialchars($categoryRow['name']) . '</option>';
                                }
                                mysqli_close($conn);
                                ?>
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="productDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="productDescription" name="productDescription" rows="3" required></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="productImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editProductForm" enctype="multipart/form-data" action="edit_product_process.php"
                    method="POST">
                    <input type="hidden" id="editProductId" name="productId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="editProductName" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="editProductName" name="productName"
                                    required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editProductQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="editProductQuantity"
                                    name="productQuantity" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editServiceCategory" class="form-label">Category</label>
                                <select class="form-select" id="editServiceCategory" name="serviceCategory" required>
                                    <?php
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    $categoryQuery = "SELECT id, name FROM service_categories";
                                    $categoryResult = mysqli_query($conn, $categoryQuery);
                                    while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
                                        echo '<option value="' . $categoryRow['id'] . '">' . htmlspecialchars($categoryRow['name']) . '</option>';
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="editProductDescription" class="form-label">Description</label>
                                <textarea class="form-control" id="editProductDescription" name="productDescription"
                                    rows="3" required></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="editProductImage" class="form-label">New Image (optional)</label>
                                <input type="file" class="form-control" id="editProductImage" name="productImage"
                                    accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div>
    </div>
    <div class="col-md-12 mb-4 text-end">
        <!-- Stock IN Button -->
        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#stockManagementModal">
            <i class="fas fa-cogs"></i> Stock IN
        </button>

        <!-- Stock OUT Button -->
        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="   #stockOutModal">
            <i class="fas fa-minus-circle"></i> Stock OUT
        </button>
    </div>
    <!-- Stock Management Modal -->
    <div class="modal fade" id="stockManagementModal" tabindex="-1" aria-labelledby="stockManagementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockManagementModalLabel">Stock IN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="stockManagementForm" action="product_stockIn.php" method="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="stockProductName" class="form-label">Product Name</label>
                                <select class="form-select" id="stockProductName" name="stockProductName">
                                    <option value="" disabled selected>Select a product</option>
                                    <?php
                                    // Database connection code
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    $query = "SELECT id, name FROM products";
                                    $result = mysqli_query($conn, $query);
                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                        }
                                    } else {
                                        echo "<option value=\"\">Error fetching products: " . mysqli_error($conn) . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stockQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="stockQuantity" name="stockQuantity"
                                    required>
                            </div>
                            <div id="addedProducts" class="mt-3">
                                <h6>Added Products:</h6>
                                <ul id="productList" class="list-group"></ul>
                            </div>
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-success" onclick="addProduct()">
                                    <i class="fas fa-plus"></i> Add Product
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary">Update Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Stock OUT Modal -->
    <div class="modal fade" id="stockOutModal" tabindex="-1" aria-labelledby="stockOutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="stockOutModalLabel">Stock OUT</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="stockOutForm" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="stockOutProductName" class="form-label">Product Name</label>
                                <select class="form-select" id="stockOutProductName" name="stockOutProductName">
                                    <option value="" disabled selected>Select a product</option>
                                    <?php
                                    // Database connection code
                                    $conn = new mysqli($servername, $username, $password, $dbname);
                                    if ($conn->connect_error) {
                                        die("Connection failed: " . $conn->connect_error);
                                    }
                                    $query = "SELECT id, name FROM products";
                                    $result = mysqli_query($conn, $query);
                                    if ($result) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['name']) . '</option>';
                                        }
                                    } else {
                                        echo "<option value=\"\">Error fetching products: " . mysqli_error($conn) . "</option>";
                                    }
                                    mysqli_close($conn);
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="stockOutQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="stockOutQuantity" name="stockOutQuantity"
                                    required min="1">
                            </div>
                            <div id="removedProducts" class="mt-3">
                                <h6>Removed Products:</h6>
                                <ul id="productOutList" class="list-group"></ul>
                            </div>
                            <div class="col-12 mb-3">
                                <button type="button" class="btn btn-danger" onclick="addProductToRemove()">
                                    <i class="fas fa-minus"></i> Remove Product
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="submit" class="btn btn-danger">Update Stock OUT</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Footer Section -->
    <footer class="text-center bg-light-purple footer-spacing">
        <p class="mb-0">&copy; 2024 Bernadette Wellness Spa. All rights reserved.</p>
    </footer>

    <!-- DataTables CSS and JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            $('#productTable').DataTable();
        });

        function openEditModal(id, name, description, quantity, category, status, image) {
            $('#editProductId').val(id);
            $('#editProductName').val(name);
            $('#editProductDescription').val(description);
            $('#editProductQuantity').val(quantity);
            $('#editProductCategory').val(category); // Ensure this matches the select ID
            $('#editProductStatus').val(status);
            $('#editProductModal').modal('show');
        }
    </script>
    <script>

    </script>
    <!-- Inline JavaScript for Sidebar Toggle -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }
    </script>
    <script>
        let addedProducts = {};

        function addProduct() {
            const productSelect = document.getElementById('stockProductName');
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            const quantityInput = document.getElementById('stockQuantity');

            const productId = selectedProduct.value;
            const productName = selectedProduct.text;
            const quantity = parseInt(quantityInput.value);

            // Ensure valid selection and quantity
            if (!productId || (isNaN(quantity) || quantity <= 0)) {
                alert('Please select a product and enter a valid quantity.');
                return;
            }

            // Check if product is already in the list
            if (addedProducts[productId]) {
                addedProducts[productId].quantity += quantity;
            } else {
                addedProducts[productId] = {
                    name: productName,
                    quantity
                };
            }

            // Update the UI list
            updateProductList();

            // Clear inputs
            productSelect.selectedIndex = 0;
            quantityInput.value = '';

            // Remove required attribute on quantity if a product has been added
            quantityInput.removeAttribute('required');
        }

        function updateProductList() {
            const productList = document.getElementById('productList');
            productList.innerHTML = '';

            for (const id in addedProducts) {
                const {
                    name,
                    quantity
                } = addedProducts[id];
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.textContent = `${name} - Quantity: ${quantity}`;

                // Quantity input for direct UI updating
                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.value = quantity;
                quantityInput.className = 'form-control mx-2';
                quantityInput.style.width = '80px';
                quantityInput.min = 1;

                quantityInput.onchange = () => {
                    const newQuantity = parseInt(quantityInput.value);
                    if (newQuantity > 0) {
                        addedProducts[id].quantity = newQuantity;
                        updateProductList();
                    } else {
                        alert('Quantity must be positive.');
                        quantityInput.value = quantity;
                    }
                };

                listItem.appendChild(quantityInput);

                // Remove button
                const removeButton = document.createElement('button');
                removeButton.className = 'btn btn-danger btn-sm ms-2';
                removeButton.textContent = 'Remove';
                removeButton.onclick = () => {
                    removeProduct(id);
                };

                listItem.appendChild(removeButton);
                productList.appendChild(listItem);
            }
        }

        function removeProduct(productId) {
            delete addedProducts[productId];
            updateProductList();

            // If no products, re-enable required attribute on quantity
            if (Object.keys(addedProducts).length === 0) {
                document.getElementById('stockQuantity').setAttribute('required', 'required');
            }
        }

        // Form submission handling
        document.getElementById('stockManagementForm').onsubmit = async function (e) {
            e.preventDefault();

            if (Object.keys(addedProducts).length === 0) {
                Swal.fire('Error', 'Please add at least one product before updating stock.', 'error');
                return;
            }

            const formData = new FormData();
            formData.append('addedProducts', JSON.stringify(addedProducts));

            try {
                const response = await fetch('product_stockIn.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    Swal.fire('Stock Updated', 'Stock updated successfully!', 'success').then(() => {
                        addedProducts = {};
                        updateProductList();
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                console.error(error);
                Swal.fire('Error', 'An unexpected error occurred. Please try again.', 'error');
            }
        };
    </script>
    <script>
        let removedProducts = {}; // Object to hold removed products and their quantities

        // Function to add a product to the removal list
        function addProductToRemove() {
            const productSelect = document.getElementById('stockOutProductName');
            const selectedProduct = productSelect.options[productSelect.selectedIndex];
            const quantityInput = document.getElementById('stockOutQuantity');

            const productId = selectedProduct.value;
            const productName = selectedProduct.text;
            const quantity = parseInt(quantityInput.value);

            // Validate product selection and quantity
            if (!productId || (isNaN(quantity) || quantity <= 0)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Input',
                    text: 'Please select a product and enter a valid quantity.',
                });
                return;
            }

            // Add or update the product in the removedProducts object
            if (removedProducts[productId]) {
                removedProducts[productId].quantity += quantity;
            } else {
                removedProducts[productId] = {
                    name: productName,
                    quantity
                };
            }

            updateRemovedProductList(); // Update the product list display
            productSelect.selectedIndex = 0; // Reset the dropdown selection
            quantityInput.value = ''; // Clear the quantity input field

            // Remove required attribute on quantity if a product has been added
            if (Object.keys(removedProducts).length > 0) {
                quantityInput.removeAttribute('required');
            }
        }

        // Function to update the list of removed products
        function updateRemovedProductList() {
            const productOutList = document.getElementById('productOutList');
            productOutList.innerHTML = ''; // Clear the list before re-rendering

            // Loop through the removedProducts object and create list items for each product
            for (const id in removedProducts) {
                const {
                    name,
                    quantity
                } = removedProducts[id];
                const listItem = document.createElement('li');
                listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                listItem.textContent = `${name} - Quantity: ${quantity}`;

                // Create a quantity input to allow modification
                const quantityInput = document.createElement('input');
                quantityInput.type = 'number';
                quantityInput.value = quantity;
                quantityInput.className = 'form-control mx-2';
                quantityInput.style.width = '80px';
                quantityInput.min = 1;

                // Update quantity when the input changes
                quantityInput.onchange = () => {
                    const newQuantity = parseInt(quantityInput.value);
                    if (newQuantity > 0) {
                        removedProducts[id].quantity = newQuantity;
                        updateRemovedProductList(); // Re-render the list
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Quantity',
                            text: 'Quantity must be positive.',
                        });
                        quantityInput.value = quantity; // Reset the input to the original value
                    }
                };

                // Add the quantity input to the list item
                listItem.appendChild(quantityInput);

                // Create the "Remove" button
                const removeButton = document.createElement('button');
                removeButton.className = 'btn btn-danger btn-sm ms-2';
                removeButton.textContent = 'Remove';
                removeButton.onclick = () => {
                    removeProduct(id);
                };

                // Add the remove button to the list item
                listItem.appendChild(removeButton);

                // Append the list item to the product list
                productOutList.appendChild(listItem);
            }
        }

        // Function to remove a product from the removedProducts object
        function removeProduct(productId) {
            delete removedProducts[productId]; // Remove product from the object
            updateRemovedProductList(); // Update the product list display

            // If no products, re-enable required attribute on quantity
            if (Object.keys(removedProducts).length === 0) {
                document.getElementById('stockOutQuantity').setAttribute('required', 'required');
            }
        }
        if (isset($_POST['addedProducts'])) {
    file_put_contents('debug_log.txt', print_r($_POST['addedProducts'], true));
}
if (isset($_POST['removedProducts'])) {
    file_put_contents('debug_log.txt', print_r($_POST['removedProducts'], true));
}


        // Form submission handler
        document.getElementById('stockOutForm').onsubmit = async function (e) {
            e.preventDefault();

            // Check if there are any products in the removedProducts list
            if (Object.keys(removedProducts).length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Products Selected',
                    text: 'Please add at least one product before updating stock.',
                });
                return;
            }

            // Send the removed products to the server
            const formData = new FormData();
            formData.append('removedProducts', JSON.stringify(removedProducts));

            try {
                // Send the request to the server to update stock
                const response = await fetch('product_stockOut.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                // Handle the response
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Stock Updated',
                        text: 'Stock updated successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        removedProducts = {}; // Reset the removedProducts object
                        updateRemovedProductList(); // Clear the list
                        location.reload(); // Reload the page to reflect changes
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Error updating stock: ' + result.message,
                    });
                }
            } catch (error) {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Unexpected Error',
                    text: 'An unexpected error occurred. Please try again.',
                });
            }
        };
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