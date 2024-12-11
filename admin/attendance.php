<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>

<?php
// Ensure the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header('Location: employeeLogin.php');
    exit();
}

// Example: Update the user's session data
if (isset($_SESSION['user_id'])) {
    $_SESSION['firstname'] = 'New First Name';  // Update the user's firstname
    $_SESSION['lastname'] = 'New Last Name';  // Update the user's lastname
    $_SESSION['position'] = 'New Position';  // Update the user's position
}

// Handle attendance submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the employee_id from the form matches the logged-in user's employee_id
    if ($_POST['employee'] != $_SESSION['employee_id']) {
        // If it doesn't match, return an error response
        echo json_encode([
            'error' => true,
            'message' => 'You can only sign in with your own Employee ID.'
        ]);
        exit();
    }

    // Proceed with the time-in or time-out logic if the IDs match
    $employee_id = $_POST['employee'];
    $status = $_POST['status'];  // 'in' or 'out'

    // Insert or update the attendance record in the database
    $sql = "INSERT INTO attendance (employee_id, status, time_in, time_out, date) 
            VALUES (?, ?, NOW(), NULL, CURDATE()) 
            ON DUPLICATE KEY UPDATE status = ?, time_in = NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $employee_id, $status, $status);
    $stmt->execute();

    // Return a success message
    echo json_encode([
        'error' => false,
        'message' => 'Attendance recorded successfully.'
    ]);
}
?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Attendance
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Attendance</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>

            <!-- START: Modified by PAM -->
            <div class="box-body">
              <div class="table-responsive"> <!-- Add the table-responsive wrapper -->
                <table id="example1" class="table table-bordered">
                  <thead>
                    <th class="hidden"></th>
                    <th>Date</th>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Time In</th>
                    <th>Time Out</th>
                    <th>Tools</th>
                  </thead>
                  <tbody>
                    <?php
                      $sql = "SELECT *, employees.employee_id AS empid, attendance.id AS attid FROM attendance LEFT JOIN employees ON employees.id=attendance.employee_id ORDER BY attendance.date DESC, attendance.time_in DESC";
                      $query = $conn->query($sql);
                      while($row = $query->fetch_assoc()){
                        $status = ($row['status'])?'<span class="label label-warning pull-right">ontime</span>':'<span class="label label-danger pull-right">late</span>';
                        echo "
                          <tr>
                            <td class='hidden'></td>
                            <td>".date('M d, Y', strtotime($row['date']))."</td>
                            <td>".$row['empid']."</td>
                            <td>".$row['firstname'].' '.$row['lastname']."</td>
                            <td>".date('h:i A', strtotime($row['time_in'])).$status."</td>
                            <td>".date('h:i A', strtotime($row['time_out']))."</td>
                            <td>
                              <button class='btn btn-success btn-sm btn-flat edit' data-id='".$row['attid']."'><i class='fa fa-edit'></i> Edit</button>
                              <button class='btn btn-danger btn-sm btn-flat delete' data-id='".$row['attid']."'><i class='fa fa-trash'></i> Delete</button>
                            </td>
                          </tr>
                        ";
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- END: Modified by PAM -->

          </div>
        </div>
      </div>
    </section>   
  </div>
    
  <?php include 'includes/footer.php'; ?>
  <?php include 'includes/attendance_modal.php'; ?>
  </div>
  <?php include 'includes/scripts.php'; ?>

  <script>
  $(function(){
    $('.edit').click(function(e){
      e.preventDefault();
      $('#edit').modal('show');
      var id = $(this).data('id');
      getRow(id);
    });

    $('.delete').click(function(e){
      e.preventDefault();
      $('#delete').modal('show');
      var id = $(this).data('id');
      getRow(id);
    });
  });

  function getRow(id){
    $.ajax({
      type: 'POST',
      url: 'attendance_row.php',
      data: {id:id},
      dataType: 'json',
      success: function(response){
        $('#datepicker_edit').val(response.date);
        $('#attendance_date').html(response.date);
        $('#edit_time_in').val(response.time_in);
        $('#edit_time_out').val(response.time_out);
        $('#attid').val(response.attid);
        $('#employee_name').html(response.firstname+' '+response.lastname);
        $('#del_attid').val(response.attid);
        $('#del_employee_name').html(response.firstname+' '+response.lastname);
      }
    });
  }

  // Auto-refresh the table every 30 seconds (30000 milliseconds)
  setInterval(function(){
    loadAttendanceData();
  }, 30000); // Refresh every 30 seconds
  
  </script>

  <?php include 'includes/datatable_initializer.php'; ?>

</body>
</html>
