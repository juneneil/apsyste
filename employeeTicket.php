<?php
// Start the session
session_start();


if (!isset($_SESSION['employee_id'])) {
    header('Location: index.php');
    exit();
}

var_dump($_SESSION);


// Include the database connection file
include 'admin/includes/conn.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect the form data & sanitize inputs
    $user_id = trim($_POST['id']);
    $employee_id = trim($_POST['employee_id']);
    $voters_certificate = trim($_POST['voters_certificate']);
    $asesor_office = trim($_POST['asesor_office']);
    $business_office = trim($_POST['business_office']);
    $health_office = trim($_POST['health_office']);
    $cedula = trim($_POST['cedula']);
    $job_opportunities = trim($_POST['job_opportunities']);
    $police_clearance = trim($_POST['police_clearance']);
    $dswd = trim($_POST['dswd']);

    // Prepare the SQL query to insert data into the ticket table
$insert_query = "INSERT INTO ticket (id, employee_id, voters_certificate, asesor_office, business_office, health_office, cedula, job_opportunities, police_clearance, dswd) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param('isssssssss', $user_id, $employee_id, $voters_certificate, $asesor_office, $business_office, $health_office, $cedula, $job_opportunities, $police_clearance, $dswd);


    if ($insert_stmt->execute()) {
        echo "<script>alert('Ticket created successfully!');</script>";
        echo "<script>window.location.href='employeeTicket.php';</script>"; // Redirect to avoid form resubmission
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }

    $insert_stmt->close();
}




// Retrieve the user information from the database
$user_id = $_SESSION['employee_id'];

// Prepare the SQL query to fetch the additional information
$query = "SELECT voters_certificate, asesor_office, business_office, health_office, cedula, job_opportunities, police_clearance, dswd FROM ticket WHERE employee_id = ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind the user_id parameter to the prepared statement
$stmt->bind_param('i', $user_id);

// Execute the query
$stmt->execute();

// Store the result
$stmt->store_result();
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
            /*background-color: #f8f9fa; /* Default background color */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Optional shadow */

            background: linear-gradient(147deg, #164211, #329427, #164211);
            animation: Gradient 5s ease infinite;
            transition: all 0.2s ease-out;
            font-family: sans-serif;
        }

        .navbar-light .navbar-brand {
            color: #fff; /* AMENDED BY PAM: color: rgba(0, 0, 0, .9); (Original) */
        }

        /* ADDED BY PAM: .navbar-light .navbar-brand:hover */
        .navbar-light .navbar-brand:hover {
            color: #007bff;
        }

        .navbar-light .navbar-nav .active>.nav-link, .navbar-light .navbar-nav .nav-link.active, .navbar-light .navbar-nav .nav-link.show, .navbar-light .navbar-nav .show>.nav-link {
            color: white; /* AMENDED BY PAM: color: rgba(0, 0, 0, .9); (Original) */
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
            /*background-color: #f8f9fa;*/ /* Sidebar background color */
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
            color: white; /* AMENDED BY PAM: #333 (Original) */
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
            color: white; /* AMENDED BY PAM: #333 (Original) */
        }

        /* Media query for small screens */
        /* AMENDED BY PAM: #welcomeDashboard */
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
                left: -200px; /* Initially off-screen */ /* AMENDED BY PAM: 200px (original) */
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
            /* color: #333; */
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

        /* SECTION STYLES ADDED BY PAM: from employeeAbout.php */
        .section-box {
            background: linear-gradient(147deg, #164211, #329427, #164211); /* AMENDED BY PAM: from background-color: #ffffff; */
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            color: white; /* AMENDED BY PAM: from color: #007bff; */
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

    <script>
        // Get the employee_id from the form input and from the session
    var employeeIdFromForm = $('#employee').val();
    var loggedInEmployeeId = '<?php echo $_SESSION['employee_id']; ?>';  // Fetch from session
    </script>

    <!-- Navigation Bar -->
     <!-- AMENDED BY PAM: id = "navBar" -->
     <nav class="navbar navbar-expand-lg navbar-light bg-light" id = "navBar">
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
                        // Assuming you have retrieved the full name from your database after user authentication
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
            <li><a href="employeeAbout.php">About</a></li>
            <li><a href="index.php">Time-in/Out</a></li>
            <li><a href="employeeProfile.php">Profile</a></li>
            <li><a href="employeeTicket.php">Ticket</a></li>
            <li><a href="employeeLogin.php">
            <?php if (isset($_SESSION['user_id'])): ?> Logout <?php endif; ?>
            </a></li>
        </ul>
    </div>

    <button class="btn btn-primary ml-3" data-toggle="modal" data-target="#ticketModal">Add New Ticket</button>

    <!-- Modal for Ticket Data Input -->
    <div class="modal" id="ticketModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    <form method="POST">
                    <div class="row">
                <!-- Employee ID Section -->
                <div class="col-md-12 mb-4">
                        <div class="section-box p-4 bg-light rounded shadow">
                            <h5 class="section-title">Employee ID</h5>
                            <?php echo $_SESSION['employee_id']; ?>
                        </div>
                    </div>
                </div>
                        <div class="form-group">
                            <label for="employee_id">Employee ID:</label>
                            <input type="text" class="form-control" value="<?php echo $_SESSION['employee_id']; ?>" id="employee_id" name="employee_id" required>
                        </div>
                        <div class="form-group">
                            <label for="voters_certificate">Voters Certificate:</label>
                            <input type="text" class="form-control" id="voters_certificate" name="voters_certificate" required>
                        </div>
                        <div class="form-group">
                            <label for="asesor_office">Assessor Office:</label>
                            <input type="text" class="form-control" id="asesor_office" name="asesor_office" required>
                        </div>
                        <div class="form-group">
                            <label for="business_office">Business Office:</label>
                            <input type="text" class="form-control" id="business_office" name="business_office" required>
                        </div>
                        <div class="form-group">
                            <label for="health_office">Health Office:</label>
                            <input type="text" class="form-control" id="health_office" name="health_office" required>
                        </div>
                        <div class="form-group">
                            <label for="cedula">Cedula:</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" required>
                        </div>
                        <div class="form-group">
                            <label for="job_opportunities">Job Opportunities:</label>
                            <input type="text" class="form-control" id="job_opportunities" name="job_opportunities" required>
                        </div>
                        <div class="form-group">
                            <label for="police_clearance">Police Clearance:</label>
                            <input type="text" class="form-control" id="police_clearance" name="police_clearance" required>
                        </div>
                        <div class="form-group">
                            <label for="dswd">DSWD:</label>
                            <input type="text" class="form-control" id="dswd" name="dswd" required>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 mb-4">
    <div class="section-box p-4 bg-light rounded shadow">
        <!-- Ticket Table -->
        <div class="container mt-5">
            <h3>Your Tickets</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Voters Certificate</th>
                        <th>Assessor Office</th>
                        <th>Business Office</th>
                        <th>Health Office</th>
                        <th>Cedula</th>
                        <th>Job Opportunities</th>
                        <th>Police Clearance</th>
                        <th>DSWD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Ensure employee_id is available in session
                    if (isset($_SESSION['employee_id'])) {
                        // Get the employee_id from the session
                        $employee_id = $_SESSION['employee_id'];
                        $fetch_tickets_query = "SELECT voters_certificate, asesor_office, business_office, health_office, 
                        cedula, job_opportunities, police_clearance, dswd 
                        FROM ticket WHERE employee_id = ?";

                        $stmt = $conn->prepare($fetch_tickets_query);
                        $stmt->bind_param('s', $employee_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>{$row['voters_certificate']}</td>
                        <td>{$row['asesor_office']}</td>
                        <td>{$row['business_office']}</td>
                        <td>{$row['health_office']}</td>
                        <td>{$row['cedula']}</td>
                        <td>{$row['job_opportunities']}</td>
                        <td>{$row['police_clearance']}</td>
                        <td>{$row['dswd']}</td>
                        </tr>";
                        }
                        } else {
                        echo "<tr><td colspan='8'>No tickets found for this employee.</td></tr>";
                        }
                        $stmt->close();
                    } else {
                        // If no valid employee session found, display this message
                        echo "<tr><td colspan='8'>No valid employee session found. Please log in.</td></tr>";
                    }
                    
                    ?>
                </tbody>
            </table>
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
        /* AMENDED BY PAM: navBar */
        function toggleSidebar() {
            var menu = document.getElementById('menuBar');
            var body = document.body; // Get the body element
            var nav = document.getElementById('navBar');

            // Check if the sidebar is off-screen
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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
