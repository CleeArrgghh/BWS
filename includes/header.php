<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bernadette Wellness Spa</title>

    <!-- External Stylesheets -->
    <link rel="stylesheet" href="../bws_ui/resources/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../bws_ui/resources/fontawesome/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Google Recaptcha -->
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="../bws_ui/resources/service_style.css">

</head>

<body>

    <style>
        /* General Styles */
        :root {
            --primary-color: #6a1b9a;
            --secondary-color: #e1bee7;
            --text-color-dark: #333;
            --text-color-light: #fff;
            --background-color: #f8f9fa;
            --hover-color: #ff7f50;
            --shadow: rgba(0, 0, 0, 0.1);
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






        /* Carousel Styles */
        /* Carousel Styles */
        /* Carousel Styles */
        .carousel-section {
            position: relative;
            height: 600px;
            /* Set a fixed height for the carousel */
            overflow: hidden;
            /* Hide overflow to maintain the fixed height */
        }

        .carousel-image {
            object-fit: cover;
            /* Cover ensures the image fills the space without distorting */
            height: 100%;
            /* Set to 100% to fill the fixed height */
            width: 100%;
            /* Set to 100% to fill the width */
        }


        /* Smooth transition for the carousel */
        .carousel {
            transition: transform 0.5s ease;
            /* Adjust transition speed and effect */
        }

        /* Optional: Adjust the controls for better visibility */
        .carousel-control-prev,
        .carousel-control-next {
            filter: brightness(0) invert(1);
            /* Makes control icons more visible */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            /* Adds background to controls */
            border-radius: 50%;
            /* Round the control icons */
        }

        /* Change the color of the icons */
        .carousel-control-prev-icon::before,
        .carousel-control-next-icon::before {
            color: #ffcc00;
            /* Change to your desired color (e.g., yellow) */
            font-size: 1.5rem;
            /* Adjust size if needed */
        }

        /* Optional: Adjust hover state */
        .carousel-control-prev:hover .carousel-control-prev-icon,
        .carousel-control-next:hover .carousel-control-next-icon {
            background-color: rgba(255, 204, 0, 0.7);
            /* Lighten background on hover */
        }


        .carousel-section {
            position: relative;
            height: 600px;
            /* Set a fixed height for the carousel */
            overflow: hidden;
            /* Hide overflow to maintain the fixed height */
        }

        .carousel-image {
            object-fit: cover;
            /* Cover ensures the image fills the space without distorting */
            height: 100%;
            /* Set to 100% to fill the fixed height */
            width: 100%;
            /* Set to 100% to fill the width */
        }



        /* Smooth transition for the carousel */
        .carousel {
            transition: transform 0.5s ease;
            /* Adjust transition speed and effect */
        }

        /* Fixed position for the carousel controls */
        .carousel-control-prev,
        .carousel-control-next {
            position: absolute;
            /* Positioning them absolutely */
            top: 50%;
            /* Center vertically */
            transform: translateY(-50%);
            /* Adjust for proper centering */
            z-index: 10;
            /* Ensure they are above other elements */
            filter: brightness(0) invert(1);
            /* Makes control icons more visible */
        }

        .carousel-control-prev {
            left: 30px;
            /* Position on the left */
        }

        .carousel-control-next {
            right: 30px;
            /* Position on the right */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            /* Adds background to controls */
            border-radius: 50%;
            /* Round the control icons */
            padding: 10px;
            /* Optional: increase clickable area */
        }
        .carousel-section {
    max-width: 100%; /* Allow full width */
    margin: 0 auto; /* Center the carousel section */
    position: relative; /* Required for absolute positioning of controls */
}

.carousel-inner {
    width: 100%; /* Ensure the inner carousel takes full width */
}

.carousel-image {
    width: 100%; /* Ensure images stretch to fit the carousel */
    height: 500px; /* Keep the height as needed */
    object-fit: cover; /* Maintain aspect ratio while covering the area */
}

.carousel-control-prev,
.carousel-control-next {
    width: 5%; /* Narrower control buttons */
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.7; /* Slightly transparent for a better look */
}

.carousel-control-prev {
    left: 10px; /* Adjust to position the button more to the left */
}

.carousel-control-next {
    right: 10px; /* Adjust to position the button more to the right */
}

        /* Bounce Animation */
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }



        /* Carousel Section Styles */
        .carousel-caption {
            color: #fff;
            /* White text color for better visibility */
            text-align: center;
            /* Center text alignment */
        }

        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent black overlay */
            z-index: 1;
            /* Make sure the overlay is above the image but below the text */
        }

        .slide-title {
            font-size: 2.5rem;
            /* Adjust title size (larger) */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Shadow for realism */
        }

        .slide-text {
            font-size: 1.5rem;
            /* Adjust text size (larger) */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            /* Shadow for realism */
        }

        /* Button Styles */
        #loginButton,
        #signupButton {
            transition: background-color 0.3s, color 0.3s;
            /* Smooth transition effect */
        }

        #loginButton:hover,
        #signupButton:hover {
            animation: bounce 0.5s;
            /* Apply bounce animation on hover */
        }

        #loginButton:hover {
            background-color: rgba(255, 255, 255, 0.5);
            /* Lighten background on hover */
            color: #663399;
            /* Change text color on hover */
        }

        #signupButton:hover {
            background-color: rgba(102, 51, 153, 0.7);
            /* Change background on hover */
            color: white;
            /* Change text color on hover */
        }



        /* Service Categories Section */
        /* Service Categories Section */
        /* Service Categories Section */
        .service-categories {
            padding: 50px 0;
            background-color: #f5f5f5;
            opacity: 0;
            /* Initially hidden */
            transform: translateY(20px);
            /* Slide from below */
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
            /* Animation timing */
            /* Optional background color */

        }

        .service-categories.visible {
            opacity: 1;
            /* Fully visible */
            transform: translateY(0);
            /* Move to original position */
        }

        .category-row {
            margin-bottom: 50px;
        }

        .text-section h2 {
            font-size: 2rem;
            color: #2a3c5a;
            /* Adjust color to fit your theme */
            line-height: 1.4;
        }

        .text-section p {
            font-size: 1rem;
            color: #555;
            /* Slightly muted text color */
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .text-section .explore-button {
            padding: 10px 20px;
            background-color: transparent;
            border: 1px solid #2a3c5a;
            color: #2a3c5a;
            text-transform: uppercase;
            font-size: 0.9rem;
            text-decoration: none;
            transition: background-color 0.3s, color 0.3s;
        }

        .text-section .explore-button:hover {
            background-color: #2a3c5a;
            color: #fff;
        }

        /* Image section */
        .image-section img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            /* Optional rounded corners */
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .category-row {
                flex-direction: column;
            }

            .text-section {
                text-align: center;
                padding: 15px;
            }

            .image-section {
                text-align: center;
                margin-top: 20px;
            }

            .image-section img {
                max-width: 90%;
                /* Adjust for smaller screens */
            }
        }

        /* Map Container Styles */
        .map-container {
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            background: var(--background-color);
            border-radius: 8px;
            box-shadow: 0 4px 8px var(--shadow);
            margin: 0 auto;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Gallery Styles */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .gallery-item img {
            object-fit: cover;
            width: 100%;
            height: 100%;
            transition: transform 0.3s ease;
            border-radius: 8px;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        /* Modal Styles */
        .modal-dialog {
            max-width: 90%;
        }

        .modal-body {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-body img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
            border-radius: 8px;
        }
    </style>
    <style>
        /* Fade-in Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Optional for smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-in;
        }



        @keyframes fadeInCarousel {
            from {
                opacity: 0;
                transform: translateY(20px);
                /* Optional: adds a slight upward movement */
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .carousel-item {
            animation: fadeInCarousel 0.5s ease-in-out;
        }




        /* Custom CSS */
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            background-color: #6f42c1;
            /* Use your preferred color */
        }

        .card-header h5 {
            margin: 0;
        }

        .bg-purple {
            background-color: #6f42c1;
            /* Customize the purple color */
        }

        .icon-hover:hover {
            background-color: rgba(111, 66, 193, 0.1);
        }

        .icon {
            font-size: 1.5rem;
            /* Adjust the icon size */
        }


        /* for log in and sign up  keyframe */
        /* for log in and sign up  keyframe */
        /* for log in and sign up  keyframe */

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }


        
    </style>