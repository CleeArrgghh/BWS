<?php
ob_start(); // Start output buffering
session_start(); // Make sure this is after output buffering
include_once '../bws_ui/includes/header.php';
include_once '../bws_ui/db_connection/db_connection.php';

// Fetch services data from the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$query = "SELECT id, name, price FROM services";
$result = mysqli_query($conn, $query);
$services = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $services[] = $row;
    }
}
mysqli_close($conn);
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
    <title>Discount Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <style>
        h2 {
            text-align: center;
            color: #5b2a82;
            margin-top: 10px;
            margin-bottom: 30px;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background-color: #f8f9fa;
            transition: left 0.3s ease;
            padding: 20px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
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

        /* Table Styling */
        .table-wrapper {
            background-color: #ffffff;
            max-height: 400px;
            overflow-y: auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
        }

        table.display {
            width: 100%;
            background-color: #ffffff;
            border-collapse: collapse;
        }

        table.display th,
        table.display td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        table.display th {
            background-color: #5b2a82;
            color: white;
        }

        table.display tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table.display tbody tr:hover {
            background-color: #ece4f5;
        }

        /* Modal Styling */
        #addDiscountModal {
            display: none;
            position: fixed;
            z-index: 1050;
            padding-top: 60px;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: auto;
            padding: 20px;
            border-radius: 15px;
            width: 80%;
            max-width: 500px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3);
        }

        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-dialog {
            max-width: 500px;
            margin: 1.75rem auto;
        }

        .modal-body {
            padding: 1.5rem;
        }

        .modal-header {
        border-top-left-radius: 15px;
        border-top-right-radius: 15px;
        }

        .modal-title {
            font-weight: 600;
        }

        /* Close Button */
        .close {
            cursor: pointer;
            font-size: 24px;
        }

        .close:hover {
            color: #000;
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

        #addDiscountBtn {
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 8px;
        }

        #addDiscountBtn:hover {
            background-color: #218838;
        }

        /* Action Buttons */
        .action-buttons .btn {
            font-size: 14px;
            padding: 4px 8px;
        }


        .bg-light-purple {
            background-color: #f0eaff;
        }

        .card-fixed-height {
            min-height: 500px;
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
                /* Main Background */
                body {
            background-color: #f4e9ff;
            font-family: Arial, sans-serif;
        }

        /* Container and Header */
        .container {
            margin-top: 20px;
        }

        .page-title {
            text-align: center;
            color: #333;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        /* Add Discount Button */
        .add-discount-btn {
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .add-discount-btn i {
            margin-right: 8px;
        }

        /* Table Container and Styling */
        .table-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background-color: #6a1b9a;
            color: #ffffff;
            text-align: center;
            padding: 10px;
        }

        .table td {
            text-align: center;
            padding: 10px;
            vertical-align: middle;
        }

        .table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Action Buttons */
        .btn-edit, .btn-delete {
            font-size: 14px;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
        }

        .btn-edit {
            background-color: #ffc107;
        }

        .btn-delete {
            background-color: #dc3545;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

<body>
    <div class="container mt-4">
        <div class="mb-3">
            <button id="addDiscountBtn" class="btn btn-success"><i class="fas fa-plus"></i> Add Discount</button>
        </div>

        <div id="addDiscountModal" class="modal" style="display:none;">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="close" style="cursor:pointer; float: right !important;">&times;</span>
                </div>
                <form id="discountForm">
                    <label for="serviceName">Service Name:</label>
                    <select id="serviceName" name="serviceName" class="form-select mb-3" required>
                        <option value="">Select a service</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo htmlspecialchars($service['id']); ?>"
                                data-price="<?php echo $service['price']; ?>">
                                <?php echo htmlspecialchars($service['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" class="form-control mb-3" readonly>
                    <label for="discount">Discount (%):</label>
                    <select id="discount" name="discount" class="form-select mb-3">
                        <option value="0">0%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                        <option value="30">30%</option>
                    </select>
                    <label for="discountedPrice">Discounted Price:</label>
                    <input type="text" id="discountedPrice" class="form-control mb-3" readonly>
                    <label for="startTime">Start Time:</label>
                    <input type="datetime-local" id="startTime" name="startTime" class="form-control mb-3" required>
                    <label for="endTime">End Time:</label>
                    <input type="datetime-local" id="endTime" name="endTime" class="form-control mb-3" required>
                    <button type="button" id="saveDiscountBtn" class="btn btn-primary w-100">Save</button>
                </form>
            </div>
        </div>
        <!-- Edit Discount Modal -->
        <div id="editDiscountModal" class="modal" style="display:none;">
            <div class="modal-content">
            <span class="close" style="cursor:pointer; float: right !important;">&times;</span>
                <h3>Edit Discount</h3>
                <form id="editDiscountForm">
                    <input type="hidden" id="editServiceId" name="service_id"> <!-- Hidden field for service_id -->
                    <label for="editServiceName">Service Name:</label>
                    <select id="editServiceName" name="serviceName" class="form-select mb-3" disabled></select>
                    <label for="editPrice">Price:</label>
                    <input type="number" id="editPrice" name="price" class="form-control mb-3" readonly>
                    <label for="editDiscount">Discount (%):</label>
                    <select id="editDiscount" name="discount" class="form-select mb-3">
                        <option value="0">0%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
                        <option value="30">30%</option>
                    </select>
                    <label for="editDiscountedPrice">Discounted Price:</label>
                    <input type="text" id="editDiscountedPrice" class="form-control mb-3" readonly>
                    <label for="editStartTime">Start Time:</label>
                    <input type="datetime-local" id="editStartTime" name="startTime" class="form-control mb-3" required>
                    <label for="editEndTime">End Time:</label>
                    <input type="datetime-local" id="editEndTime" name="endTime" class="form-control mb-3" required>
                    <button type="button" id="updateDiscountBtn" class="btn btn-primary w-100">Update</button>
                </form>
            </div>
        </div>


        <div class="table-wrapper">
        <h2>Discount Management</h2>
            <table id="servicesTable" class="table table-striped table-bordered display" style="width:100%">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Discounted Price</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <div id="serviceDisplayArea"></div>
    </div>

    <script>
$(document).ready(function () {
    table = $('#servicesTable').DataTable({
        responsive: true,
        pageLength: 5,
        lengthMenu: [5, 10, 25, 50, 100],
        ajax: {
            url: 'fetch_discounts.php',
            dataSrc: 'data'
        },
        columns: [
            { data: 0 },
            { data: 1 },
            { data: 2 },
            { data: 3 },
            { data: 4 },
            { data: 5 },
            {
                data: 6,
                render: function (data, type, row) {
                    return `
                        <button class="edit btn btn-sm btn-primary">Edit</button>
                        <button class="delete btn btn-sm btn-danger">Delete</button>
                    `;
                }
            }
        ]
    });

        function formatDateToDatetimeLocal(dateString) {
            const date = new Date(dateString);
            if (isNaN(date.getTime())) {
                console.error("Invalid date format:", dateString);
                return ""; // Return an empty string if the date is invalid
            }
            // Adjust the time zone offset if needed
            const offsetDate = new Date(date.getTime() - date.getTimezoneOffset() * 60000);
            return offsetDate.toISOString().slice(0, 16); // Format to yyyy-MM-ddTHH:mm
        }



        $('#servicesTable').on('click', '.edit', function () {
            var data = table.row($(this).parents('tr')).data();
            var serviceId = data[6]; // Assuming `service_id` is at index 6

            // Set values in modal fields
            $('#editServiceId').val(serviceId);
            $('#editServiceName').html('<option selected>' + data[0] + '</option>').prop('disabled', true);
            $('#editPrice').val(parseFloat(data[1].replace(/[^\d.-]/g, '')));
            $('#editDiscount').val(data[2]);
            $('#editDiscountedPrice').val(data[3]);

            // Format and set start and end times
            $('#editStartTime').val(formatDateToDatetimeLocal(data[4]));
            $('#editEndTime').val(formatDateToDatetimeLocal(data[5]));

            $('#editDiscountModal').show();
        });


        $('#updateDiscountBtn').on('click', function () {
            var serviceId = $('#editServiceId').val();
            var discountPercentage = $('#editDiscount').val();
            var startTime = $('#editStartTime').val();
            var endTime = $('#editEndTime').val();

            $.ajax({
                url: 'edit_discount_process.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    service_id: serviceId,
                    discount_percentage: discountPercentage,
                    start_time: startTime,
                    end_time: endTime
                },
                success: function (response) {
                    swal("Success", response.message, "success");
                    if (response.status === "success") {
                        table.ajax.reload(null, false);
                        $('#editDiscountForm')[0].reset();
                        $('#editDiscountModal').hide();
                    }
                },
                error: function () {
                    swal("Error", "An error occurred. Please try again.", "error");
                }
            });
        });
    });


// Delete button handler
$('#servicesTable').on('click', '.delete', function () {
        var data = table.row($(this).parents('tr')).data();
        var serviceId = data[6]; // Update this index if service_id is located in a different position

        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this discount!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url: 'delete_discount_process.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        service_id: serviceId
                    },
                    success: function (response) {
                        swal("Success", response.message, "success");
                        if (response.status === "success") {
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function () {
                        swal("Error", "An error occurred. Please try again.", "error");
                    }
                });
            }
        });
    });

        $(document).ready(function () {
    // Open the Add Discount Modal
    $('#addDiscountBtn').on('click', function () {
        $('#addDiscountModal').show();
    });

    // Close Modal when X button is clicked
    $('.close').on('click', function () {
        $('#addDiscountModal').hide();
    });

    // Calculate Discounted Price when selecting a discount
    $('#discount').on('change', function () {
        var price = parseFloat($('#price').val());
        var discountPercentage = parseFloat($(this).val());
        var discountedPrice = price - (price * discountPercentage / 100);
        $('#discountedPrice').val(discountedPrice.toFixed(2));
    });

    // Populate the Price when a Service is selected
    $('#serviceName').on('change', function () {
        var selectedOption = $(this).find(':selected');
        var price = selectedOption.data('price');
        $('#price').val(price);

        // Reset discount and calculate initial discounted price
        $('#discount').val(0);
        $('#discountedPrice').val(price);
    });

    // Handle Save Discount Button Click
    $('#saveDiscountBtn').on('click', function () {
        var serviceId = $('#serviceName').val();
        var discountPercentage = $('#discount').val();
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();

        // Perform AJAX request to save the discount
        $.ajax({
            url: 'add_discount_process.php',
            type: 'POST',
            dataType: 'json',
            data: {
                service_id: serviceId,
                discount_percentage: discountPercentage,
                start_time: startTime,
                end_time: endTime
            },
            success: function (response) {
                if (response.status === "success") {
                    swal("Success", response.message, "success");
                    $('#discountForm')[0].reset();  // Reset the form
                    $('#addDiscountModal').hide();  // Close the modal
                    $('#servicesTable').DataTable().ajax.reload(null, false);  // Reload the table
                } else {
                    swal("Error", response.message, "error");
                }
            },
            error: function () {
                swal("Error", "An error occurred while adding the discount.", "error");
            }
        });
    });
});
</script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('open');
    }
</script>
<script>
    // Close Edit Discount Modal when the close button is clicked
$('.close').on('click', function () {
    $('#editDiscountModal').hide();
});

// Close Edit Discount Modal when clicking outside of it
$(window).on('click', function (event) {
    if (event.target.id === 'editDiscountModal') {
        $('#editDiscountModal').hide();
    }
});

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