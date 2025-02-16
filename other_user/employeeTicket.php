<?php
// Start the session
session_start();

// If the user is not logged in, redirect to employeeLogin.php
if (!isset($_SESSION['user_id'])) {
    header('Location: employeeLogin.php');  // Redirect to login page
    exit();  // Stop further execution
}

// Include the database connection file
include 'admin/includes/conn.php';

// Retrieve the user information from the database
$user_id = $_SESSION['user_id'];

// Prepare the SQL query to fetch the additional information
$query = "SELECT contact_info, address, gender, created_on, schedule_id FROM employees WHERE id = ?";

// Prepare the statement
$stmt = $conn->prepare($query);

// Bind the user_id parameter to the prepared statement
$stmt->bind_param('i', $user_id);

// Execute the query
$stmt->execute();

// Store the result
$stmt->store_result();

// Check if the query returned any result
if ($stmt->num_rows > 0) {
    // Bind the result variables
    $stmt->bind_result($contact_info, $address, $gender, $created_on, $schedule_id);
    $stmt->fetch(); // Fetch the result into the variables
} else {
    // Handle the case when no data is found
    $contact_info = 'Contact info not available.';
    $address = 'Address not available.';
    $gender = 'Gender not available.';
    $created_on = 'Account creation date not available.';
    $schedule_id = 'Schedule not available.';
}

// Close the statement
$stmt->close();

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect the form data
    $voters_certificate = $_POST['voters_certificate'];
    $assessor_office = $_POST['assessor_office'];
    $business_office = $_POST['business_office'];
    $health_office = $_POST['health_office'];
    $cedula = $_POST['cedula'];
    $job_opportunities = $_POST['job_opportunities'];
    $police_clearance = $_POST['police_clearance'];
    $dswd = $_POST['dswd'];

    // Prepare the SQL query to insert data into the ticket table
    $insert_query = "INSERT INTO ticket (user_id, voters_certificate, assessor_office, business_office, health_office, cedula, job_opportunities, police_clearance, dswd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $insert_stmt = $conn->prepare($insert_query);

    // Bind parameters
    $insert_stmt->bind_param('issssssss', $user_id, $voters_certificate, $assessor_office, $business_office, $health_office, $cedula, $job_opportunities, $police_clearance, $dswd);

    // Execute the query
    if ($insert_stmt->execute()) {
        echo "Ticket created successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    // Close the statement
    $insert_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home of Pateros</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Button to trigger the modal -->
    <button class="btn btn-primary" data-toggle="modal" data-target="#ticketModal">Add New Ticket</button>

    <!-- Modal for Ticket Data Input -->
    <div class="modal" id="ticketModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title">Add New Ticket</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form method="POST">
                        <div class="form-group">
                            <label for="voters_certificate">Voters Certificate:</label>
                            <input type="text" class="form-control" id="voters_certificate" name="voters_certificate" required>
                        </div>
                        <div class="form-group">
                            <label for="assessor_office">Assessor Office:</label>
                            <input type="text" class="form-control" id="assessor_office" name="assessor_office" required>
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
                // Query to fetch tickets for the logged-in user
                $fetch_tickets_query = "SELECT voters_certificate, assessor_office, business_office, health_office, cedula, job_opportunities, police_clearance, dswd FROM ticket WHERE id = $user_id";

                // Execute the query
                $result = $conn->query($fetch_tickets_query);

                // Check if there are any records
                if ($result->num_rows > 0) {
                    // Fetch the records
                    while ($row = $result->fetch_assoc()) {
                        // Display each row in the table
                        echo "<tr>
                                <td>{$row['voters_certificate']}</td>
                                <td>{$row['assessor_office']}</td>
                                <td>{$row['business_office']}</td>
                                <td>{$row['health_office']}</td>
                                <td>{$row['cedula']}</td>
                                <td>{$row['job_opportunities']}</td>
                                <td>{$row['police_clearance']}</td>
                                <td>{$row['dswd']}</td>
                              </tr>";
                    }
                } else {
                    // Handle case when no records are found
                    echo "<tr><td colspan='8'>No tickets found for this user.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Pateros Municipality. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
