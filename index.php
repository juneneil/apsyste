<?php
// Start the session
session_start();

var_dump($_SESSION);  // Output session data

// If the user is not logged in, redirect to employeeLogin.php
if (!isset($_SESSION['user_id'])) {
  header('Location: employeeLogin.php');  // Redirect to login page
  exit();  // Stop further execution
}

// Example: Update the user’s session data
if (isset($_SESSION['user_id'])) {
  $_SESSION['firstname'] = 'New First Name';  // Update the user's firstname
  $_SESSION['lastname'] = 'New Last Name';  // Update the user's lastname
  $_SESSION['position'] = 'New Position';  // Update the user's position
}
?>

<?php include 'header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<p id="date"></p>
      <p id="time" class="bold"></p>
  	</div>
  
  	<div class="login-box-body">
    	<h4 class="login-box-msg">Enter Employee ID</h4>

    	<form id="attendance">
  <div class="form-group">
    <select class="form-control" name="status">
      <option value="in">Time In</option>
      <option value="out">Time Out</option>
    </select>
  </div>

  <div class="form-group has-feedback">
    <input type="text" class="form-control input-lg" id="employee" name="employee" required>
    <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
  </div>

  <!-- Hidden input for employee_id -->
  <input type="hidden" name="employee_id" value="<?php echo $_SESSION['employee_id']; ?>">

  <div class="row">
    <div class="col-xs-4">
      <button type="submit" class="btn btn-primary btn-block btn-flat" name="signin"><i class="fa fa-sign-in"></i> Sign In</button>
    </div>
  </div>
</form>

      <!-- Display logout button if the user is logged in -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="employeeLogin.php" method="post">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    <?php endif; ?>
  	</div>
		<div class="alert alert-success alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-check"></i> <span class="message"></span></span>
    </div>
		<div class="alert alert-danger alert-dismissible mt20 text-center" style="display:none;">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <span class="result"><i class="icon fa fa-warning"></i> <span class="message"></span></span>
    </div>
  		
</div>
	
<?php include 'scripts.php' ?>
<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#date').html(momentNow.format('dddd').substring(0, 3).toUpperCase() + ' - ' + momentNow.format('MMMM DD, YYYY'));
    $('#time').html(momentNow.format('hh:mm:ss A'));
  }, 100);

  $('#attendance').submit(function(e){
    e.preventDefault();

    // Get the employee_id from the form input and from the session
    var employeeIdFromForm = $('#employee').val();
    var loggedInEmployeeId = '<?php echo $_SESSION['employee_id']; ?>';  // Fetch from session

    // Check if the employee_id from the form matches the logged-in user's employee_id
    if(employeeIdFromForm != loggedInEmployeeId) {
      // Display error if they try to use another user's employee_id
      $('.alert').hide();
      $('.alert-danger').show();
      $('.message').html('You can only sign in with your own Employee ID.');
      return;  // Stop form submission
    }

    var attendance = $(this).serialize();
    $.ajax({
      type: 'POST',
      url: 'attendance.php',
      data: attendance,
      dataType: 'json',
      success: function(response){
        if(response.error){
          $('.alert').hide();
          $('.alert-danger').show();
          $('.message').html(response.message);
        }
        else{
          $('.alert').hide();
          $('.alert-success').show();
          $('.message').html(response.message);
          $('#employee').val('');  // Clear the input field
        }
      }
    });
  });
});
</script>
</body>
</html>