<?php
// Start the session
session_start();

// If the user is not logged in, redirect to employeeLogin.php
if (!isset($_SESSION['user_id'])) {
  header('Location: employeeLogin.php');  // Redirect to login page
  exit();  // Stop further execution
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home of Pateros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            padding-top: 0;
            background-image: url('Pateros-Municipal-Hall.jpg');
            background-size: cover; /* Makes the image cover the entire body */
            background-position: center; /* Centers the image */
            background-attachment: fixed; /* Makes the background fixed when scrolling */
            color: #fff; /* White text color for better contrast */
            transition: margin-left 0.3s ease-in-out; /* Smooth transition for body sliding */

            min-height: 100vh; /* Ensure body takes at least the height of the viewport */
            overflow-x: hidden; /* Prevent horizontal scrolling */
            overflow-y: auto; /* Allow vertical scrolling when content overflows */
        }
        
        .navbar {
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow */

            background: linear-gradient(147deg, #164211, #329427, #164211);
            animation: Gradient 5s ease infinite;
            transition: all 0.2s ease-out;
            font-family: sans-serif;
        }

        .navbar-light .navbar-brand {
            color: #fff;
        }

        /* ADDED BY PAM: .navbar-light .navbar-brand:hover */
        .navbar-light .navbar-brand:hover {
            color: #007bff;
        }

        .navbar-light .navbar-nav .active>.nav-link, .navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .nav-link.show, .navbar-light .navbar-nav .show>.nav-link {
            color: white;
        }

        .navbar-toggler {
            border: none;
            background-color: transparent;
        }

        /* Sidebar Styles */
        .menu-bar {
            list-style-type: none;
            padding: 0;
            position: fixed;
            top: 70px; /* Positioned below the navbar */
            left: -250px; /* Initially off-screen */
            width: 250px;
            height: 100%; /* Full height */
            background-color: #f8f9fa; /* Sidebar background color */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            z-index: 999;
            transition: left 0.3s ease-in-out; /* Smooth transition for sliding in and out */

            background: linear-gradient(147deg, #164211, #329427, #164211);
            animation: Gradient 5s ease infinite;
            transition: all 0.2s ease-out;
            font-family: sans-serif;
            color: white;
        }

        .menu-bar li {
            padding: 8px 12px;
        }

        .menu-bar li a {
            text-decoration: none;
            color: white;
        }

        .menu-bar li a:hover {
            color: #007bff;
        }

        .navbar-brand img {
            width: 40px; /* Adjust the size of the logo */
            height: auto;
            margin-right: 10px; /* Adds space between the logo and text */
        }

        /* Sidebar toggle button */
        .sidebar-toggle {
            font-size: 24px;
            cursor: pointer;
            color: white;
        }

        /* Media query for small screens */
        @media (max-width: 767px) {
            .navbar {
                background-color: #f8f9fa; /* Ensure navbar has the same background color */
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Keep the shadow */
            }

            #welcomeDashboard {
                position: relative;
            }

            body {
                position: absolute;
            }

            .menu-bar {
                width: 200px; /* Wider sidebar on small screens */
                position: absolute;
                top: 80px; /* Below the navbar */
                left: -200px; /* Initially off-screen */
                width: auto; /* Full width on smaller screens */
                height: 100%; /* Adjust height accordingly */
            }

            .navbar-nav {
                flex-direction: row; /* Align items horizontally */
            }

            .navbar-nav .nav-item {
                margin-left: 10px;
                margin-right: 10px;
            }

            .navbar-collapse {
                flex-grow: 0;
            }

            /* Positioning for burger icon and 'John Doe' on smaller screens */
            .navbar-nav .nav-item:first-child {
                order: -1; /* Move 'John Doe' to the right side of the navbar */
            }
        }

        footer {
            background-color: #f8f9fa;
            padding: 5px 0;
            text-align: center;
            position: fixed;
            width: 100%;
            bottom: 0;

            background: linear-gradient(147deg, #164211, #329427, #164211);
            animation: Gradient 5s ease infinite;
            transition: all 0.2s ease-out;
            font-family: sans-serif;

            opacity: 0.5;
        }

        .section-box {
            background: linear-gradient(147deg, #164211, #329427, #164211); /* AMENDED BY PAM: from background-color: #ffffff; */
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: #007bff;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .mb-4 {
            margin-bottom: 30px;
        }

        /* Styling for the box */
        .p-4 {
            padding: 20px;
        }

        .rounded {
            border-radius: 8px;
        }

        .shadow {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        /* Adding spacing between sections */
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" id="navBar">
        <a class="navbar-brand" href="employeeHome.php">
            <img src="Pateros-logo-duck.png" alt="Pateros Logo"> <!-- Logo Image -->
            Pateros Municipality
        </a>
        
        <!-- Sidebar Toggle Button -->
        <a href="#" class="sidebar-toggle" onclick="toggleSidebar()">
            <span class="sr-only">Toggle navigation</span>
            &#9776; <!-- Hamburger icon (three horizontal bars) -->
        </a>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">
                        <?php
                        // Display the user's full name (First Name and Last Name)
                        echo 'Welcome, ' . $_SESSION['firstname'] . ' ' . $_SESSION['lastname'];
                        ?>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Menu Bar (Sidebar) -->
    <div class="menu-bar" id="menuBar">
        <ul>
            <li><a href="employeeHome.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="index.php">Time-in/Out</a></li>
            <li><a href="employeeProfile.php">Profile</a></li>
            <li><a href="employeeLogin.php">
                <?php if (isset($_SESSION['user_id'])): ?> Logout <?php endif; ?>
            </a></li>
        </ul>
    </div>

    <!-- Main Content Area -->
    <div class="container" id="welcomeDashboard">
        <div class="row">
            <!-- About Section -->
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">About</h2>
                    <p>Pateros, officially the Municipality of Pateros; Filipino: Bayan ng Pateros, is the lone municipality of Metro Manila, Philippines. According to the 2020 census, it has a population of 65,227 people.<br><br>

                    The municipality is famous for its duck-raising industry and especially for producing balut, a Filipino delicacy, which is a boiled, fertilized duck egg. Pateros is also known for the production of red salty eggs and "inutak", a local rice cake. Moreover, the town is known for manufacturing of "alfombra", a locally-made footwear with a carpet-like fabric on its top surface. Pateros is bordered by the highly urbanized cities of Pasig to the north, and by Taguig to the east, west and south.<br><br>

                    Pateros is the smallest municipality both in population and in land area, in Metro Manila, but it is the second most densely populated at around 37,000 inhabitants per square kilometer or 96,000 inhabitants per square mile after the capital city of Manila. Unlike its neighbors in Metro Manila, Pateros is the only municipality in the region.</p>
                </div>
            </div>
            
            <!-- Mission Section -->
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">Mission</h2>
                    <p>The next project which we are already starting now will be a powerful Ebike Solar Charging Stations for Ebike Users in Pateros. With this project, we can help Etricycle users to lower their electric charging bill possibly by half or even none at all if they will allow us to setup their own Ebike Solar Charging Station in their homes.<br><br>
                        
                    It is also to serve the public with excellence and integrity in all of our efforts.</p>
                </div>
            </div>
            
            <!-- Vision Section -->
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">Vision</h2>
                    <p><p>To become a brownout free town and some people already know that it is now the first Solar City in the Philippines for its advancement in solar projects and for its transformation as a Green City with Good Renewable Energy Technologies.<br><br>
                        
                    It is also to build a well-organized and thriving community, ensuring a better future for all.</p>
                </div>
            </div>
            
            <!-- Goals Section -->
            <div class="col-md-12 mb-4">
                <div class="section-box p-4 bg-light rounded shadow">
                    <h2 class="section-title">Goals</h2>
                    <p>Our goals include enhancing infrastructure, supporting education, and ensuring the welfare of every citizen.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Pateros Municipality. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Function to toggle the sidebar
        function toggleSidebar() {
            var menu = document.getElementById('menuBar');
            var body = document.body; // Get the body element
            var nav = document.getElementById('navBar');
            
            if (window.innerWidth > 767) {
                // For larger screens (Desktop)
                if (menu.style.left === '-250px' || menu.style.left === '') {
                    menu.style.left = '0'; // Show the sidebar by moving it into view
                    body.style.marginLeft = '250px'; // Move the body to the right
                    nav.style.marginLeft = '-250px'; // Fixed in place
                } else {
                    menu.style.left = '-250px'; // Hide the sidebar by moving it out of view
                    body.style.marginLeft = '0'; // Reset the body's margin
                    nav.style.marginLeft = '-250px'; // Fixed in place
                }
            } else {
                // For smaller screens (Mobile)
                if (menu.style.left === '-200px' || menu.style.left === '') {
                    menu.style.left = '0'; // Show the sidebar by moving it into view
                    nav.style.marginLeft = '0'; // Fixed in place
                } else {
                    menu.style.left = '-200px'; // Hide the sidebar by moving it out of view
                }
            }
        }
    </script>

</body>
</html>
