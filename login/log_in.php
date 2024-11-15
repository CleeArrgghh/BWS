<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bernadette Wellness Spa</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../../bws_ui/login/login_style/login_style.css">
    <!-- reCAPTCHA script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
        <button class="close-btn btn btn-outline-purple mb-4" onclick="toggleSidebar()" style="font-size: 1.5rem; position: absolute; right: 15px; top: 15px;">&times;</button>



        <ul class="list-unstyled">
            <li class="mb-3">
                <a href="index.php" id="homeLink" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-home fa-lg me-3 icon"></i>
                    <span>Home</span>
                </a>
            </li>
            <li class="mb-3">
                <a href="#booking" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
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
            <a href="../bws_ui/history.php" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                <i class="fas fa-history fa-lg me-3 icon"></i> <!-- Updated icon -->
                <span>History</span>
            </a>
        </li>
            <li class="mb-3">
                <a href="#aboutus" class="d-flex align-items-center text-dark text-decoration-none icon-hover list-item">
                    <i class="fas fa-info-circle fa-lg me-3 icon"></i>
                    <span>About Us</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- End of Sidebar -->

    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <!-- Title -->
                    <h2 class="card-title text-center mb-4">Login</h2>

                    <!-- Login Form -->
                    <form action="../login/process/log_in_process.php" method="post">
                        <!-- Username Field -->
                        <div class="form-group mb-3">
                            <label for="username"><i class="fas fa-user"></i> Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Your Username" required>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group mb-4">
                            <label for="password"><i class="fas fa-lock"></i> Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPassword" onclick="togglePassword()">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





    <style>
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

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../bws_ui/login/js function/show_pass_function.js"></script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="../../bws_ui/login/js function/toggle_function.js"></script>
    <script src="../../bws_ui/login/js function/calendar_function.js"></script>
    <script src="../../bws_ui/login/js function/show_pass_function.js"></script>
    <script src="../../bws_ui/login/js function/password_function.js"></script>
    <script src="../../bws_ui/login/js function/sw_home.js"></script>

</body>

</html>