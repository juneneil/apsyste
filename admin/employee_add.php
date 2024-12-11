<?php
include 'includes/session.php';

if(isset($_POST['add'])){
    // Get form data
    $email = $_POST['email'];  // Capture the email input
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $birthdate = $_POST['birthdate'];
    $contact = $_POST['contact'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $schedule = $_POST['schedule'];
    $filename = $_FILES['photo']['name'];

    // Set password as lastname (you can modify this if needed)
    $password = $lastname;  // Set password to lastname as the default

    // Check if a photo is uploaded
    if(!empty($filename)){
        move_uploaded_file($_FILES['photo']['tmp_name'], '../images/'.$filename);    
    }

    // Creating employee ID (randomly generated)
    $letters = '';
    $numbers = '';
    foreach (range('A', 'Z') as $char) {
        $letters .= $char;
    }
    for($i = 0; $i < 10; $i++){
        $numbers .= $i;
    }
    $employee_id = substr(str_shuffle($letters), 0, 3).substr(str_shuffle($numbers), 0, 9);

    // SQL query to insert employee data including email and password
    $sql = "INSERT INTO employees (employee_id, email, firstname, lastname, address, birthdate, contact_info, gender, position_id, schedule_id, photo, password, created_on) 
            VALUES ('$employee_id', '$email', '$firstname', '$lastname', '$address', '$birthdate', '$contact', '$gender', '$position', '$schedule', '$filename', '$password', NOW())";
    
    // Execute the query and check for success
    if($conn->query($sql)){
        $_SESSION['success'] = 'Employee added successfully';

        // After successfully adding the employee, you can also store their session data
        $_SESSION['user_id'] = $conn->insert_id; // Store newly created employee's user_id
        $_SESSION['email'] = $email;  // Store email
        $_SESSION['firstname'] = $firstname;  // Store firstname
        $_SESSION['lastname'] = $lastname;  // Store lastname
        $_SESSION['position'] = $position;  // Store position
        
        // Optional: Redirect to a page or update the page as needed
    } else {
        $_SESSION['error'] = $conn->error;  // Return the error if query fails
    }

} else {
    $_SESSION['error'] = 'Fill up add form first';  // Error if form was not submitted
}

header('location: employee.php');  // Redirect back to employee page
?>
