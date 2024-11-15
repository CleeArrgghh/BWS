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
            <li class="mb-3">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item" id="logoutLink">
                    <i class="fas fa-sign-out-alt fa-lg me-3 icon"></i> <!-- Log Out Icon -->
                    <span>Log Out</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>






    <!-- Header Section -->
    <header>
        <div class="logo">MyBooking - Reminder Dashboard</div>
    </header>

    <!-- Main Section -->
    <main>
        <section class="reminder-dashboard">
            <h1>Booking Reminders Dashboard</h1>
            <p>Automatically sending SMS reminders before scheduled booking times.</p>

            <!-- Admin Reminder Time Setting -->
            <div class="setting">
                <label for="reminder-time">Set Reminder Time:</label>
                <input type="number" id="reminder-time" name="reminder-time" placeholder="Enter time" value="30">
                <select id="reminder-unit">
                    <option value="minutes">Minutes</option>
                    <option value="hours">Hours</option>
                </select>
                <button type="button" id="update-reminder-time">Update Reminder Time</button>
            </div>

            <!-- Reminder Summary Table -->
            <div class="reminder-summary">
                <h2>All Bookings</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Customer Name</th>
                            <th>Date & Time</th>
                            <th>Phone Number</th>
                            <th>Countdown to SMS</th>
                            <th>Reminder Status</th>
                        </tr>
                    </thead>
                    <tbody id="booking-list">
                        <?php
                        // Database connection
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "bwsdb";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch booking data with user phone number
                        $sql = "SELECT 
                                    ab.service_names, 
                                    ab.full_name, 
                                    ab.appointment_date, 
                                    ab.appointment_time, 
                                    u.phone 
                                FROM approved_bookings AS ab
                                JOIN tbl_users AS u ON ab.user_id = u.user_id";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            $index = 0;
                            while ($row = $result->fetch_assoc()) {
                                $datetime = $row['appointment_date'] . ' ' . $row['appointment_time'];
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['service_names']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['full_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($datetime) . "</td>";
                                echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                                echo "<td class='countdown' id='countdown-$index'>Calculating...</td>";
                                echo "<td class='status pending'>Pending</td>";
                                echo "</tr>";
                                $index++;
                            }
                        } else {
                            echo "<tr><td colspan='6'>No bookings found.</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            let reminderTime = 30;
            let reminderUnit = "minutes";

            const reminderTimeInput = document.getElementById("reminder-time");
            const reminderUnitSelect = document.getElementById("reminder-unit");
            const updateReminderTimeButton = document.getElementById("update-reminder-time");

            // Load stored reminder settings from local storage
            if (localStorage.getItem("reminderTime")) {
                reminderTime = parseInt(localStorage.getItem("reminderTime"));
                reminderTimeInput.value = reminderTime;
            }

            if (localStorage.getItem("reminderUnit")) {
                reminderUnit = localStorage.getItem("reminderUnit");
                reminderUnitSelect.value = reminderUnit;
            }

            const bookingRows = document.getElementById("booking-list").children;
            const bookings = Array.from(bookingRows).map((row, index) => ({
                element: row,
                datetime: new Date(row.children[2].innerText),
                countdownElement: row.querySelector(`#countdown-${index}`),
                phone: row.children[3].innerText,
                service: row.children[0].innerText,
                statusElement: row.querySelector(".status")
            }));

            // Update reminder time based on admin input
            updateReminderTimeButton.addEventListener("click", () => {
                const newReminderTime = parseInt(reminderTimeInput.value);
                const newReminderUnit = reminderUnitSelect.value;

                if (!isNaN(newReminderTime) && newReminderTime > 0) {
                    reminderTime = newReminderTime;
                    reminderUnit = newReminderUnit;

                    localStorage.setItem("reminderTime", reminderTime);
                    localStorage.setItem("reminderUnit", reminderUnit);

                    alert(`Reminder time updated to ${reminderTime} ${reminderUnit} before booking.`);
                } else {
                    alert("Please enter a valid time.");
                }
            });

            // Function to calculate and display countdowns until SMS
            const updateCountdowns = () => {
                bookings.forEach((booking) => {
                    const countdownTime = calculateTimeUntilSMS(booking.datetime);
                    booking.countdownElement.textContent = countdownTime > 0 ?
                        formatCountdown(countdownTime) :
                        "Reminder Sent";

                    if (countdownTime <= 0 && booking.statusElement.textContent === "Pending") {
                        booking.statusElement.textContent = "Sent";
                        sendReminder(booking.phone, booking.service, booking.datetime);
                    }
                });
            };

            // Calculate time before SMS should be sent
            const calculateTimeUntilSMS = (datetime) => {
                const multiplier = reminderUnit === "hours" ? 60 * 60 * 1000 : 60 * 1000;
                const reminderMillis = reminderTime * multiplier;
                const smsSendTimeMillis = datetime.getTime() - reminderMillis;
                return smsSendTimeMillis - new Date().getTime();
            };

            // Format countdown as "HH:MM:SS"
            const formatCountdown = (ms) => {
                const totalSeconds = Math.floor(ms / 1000);
                const hours = String(Math.floor(totalSeconds / 3600)).padStart(2, "0");
                const minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, "0");
                const seconds = String(totalSeconds % 60).padStart(2, "0");

                return `${hours}:${minutes}:${seconds}`;
            };

            // Send SMS reminder directly through JavaScript
            const sendReminder = async (phone, service, datetime) => {
                const apiKey = 'ed49311fb8620f4e674df7cd10181e95';
                const message = `Reminder: Your appointment for ${service} is scheduled on ${datetime}.`;

                try {
                    const response = await fetch('https://semaphore.co/api/v4/messages', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            apikey: apiKey,
                            number: phone,
                            message: message,
                            sendername: 'Bws'
                        })
                    });

                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    const data = await response.json();
                    console.log("SMS sent response:", data);
                    if (data.status === 'queued' || data.status === 'sent') {
                        console.log("SMS sent successfully:", data);
                    } else {
                        console.error("Failed to send SMS:", data);
                    }
                } catch (error) {
                    console.error("Error sending SMS:", error);
                }
            };

            setInterval(updateCountdowns, 1000);
        });
    </script>



















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
            sidebar.classList.toggle('open'); // Add 'open' class when sidebar is active
            overlay.classList.toggle('active'); // Toggle overlay to display/hide
        }

        // Close sidebar if overlay is clicked
        document.getElementById('sidebarOverlay').addEventListener('click', toggleSidebar);
    </script>





</body>

</html>

<style>
    /* General Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body {
        background-color: #f9f9f9;
        color: #333;
    }

    header {
        display: flex;
        justify-content: center;
        background-color: #4CAF50;
        color: #fff;
        padding: 1rem 2rem;
    }

    header .logo {
        font-size: 1.5rem;
        font-weight: bold;
    }

    /* Main Section */
    main {
        padding: 2rem;
    }

    .reminder-dashboard {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .reminder-dashboard h1 {
        font-size: 1.8rem;
        margin-bottom: 0.5rem;
    }

    .setting {
        margin: 1rem 0;
    }

    .setting label {
        font-weight: bold;
    }

    .setting input[type="number"],
    .setting select {
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 0.5rem;
        width: auto;
    }

    .setting button {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 4px;
        background-color: #4CAF50;
        color: #fff;
        cursor: pointer;
    }

    .reminder-summary table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .reminder-summary th,
    .reminder-summary td {
        padding: 1rem;
        border-bottom: 1px solid #ddd;
        text-align: left;
    }

    .reminder-summary th {
        background-color: #f0f0f0;
        font-weight: bold;
    }

    .countdown {
        font-weight: bold;
    }

    .status.sent {
        color: green;
    }

    .status.pending {
        color: orange;
    }












    /* Sidebar */
    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        /* Initially hidden */
        width: 250px;
        height: 100%;
        background-color: #f8f9fa;
        transition: left 0.3s ease;
        z-index: 1000;
        /* Ensure it appears above content */
    }

    .sidebar.open {
        left: 0;
        /* Slide in when 'open' class is added */
    }

    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 900;
        /* Layer it below the sidebar */
    }

    .sidebar-overlay.active {
        display: block;
        /* Show overlay when active */
    }
</style>


<?php include '../../bws_ui/includes/footer.php' ?>