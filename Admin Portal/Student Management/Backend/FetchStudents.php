<?php
include "db.php";

// Function to get student details by ID
function getStudent($id) {
    global $conn;
    $query = "SELECT student.*, student_class.* 
              FROM student 
              INNER JOIN student_class 
              ON student.id = student_class.s_id
              WHERE student.id = '$id'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return null;
    }
}

// SQL query to fetch data from both tables
$query = "SELECT student.*, student_class.* 
          FROM student 
          INNER JOIN student_class 
          ON student.id = student_class.s_id";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if query executed successfully
if ($result) {
    echo '<div class="table-responsive">';
    echo '<table id="studentTable" class="table">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th scope="col">Roll No.</th>';
    echo '<th scope="col">Name</th>';
    echo '<th scope="col">Class</th>';
    echo '<th scope="col">Batch</th>';
    echo '<th scope="col">Actions</th>'; // New column for actions
    // Add other table headers for additional columns if needed
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    
    // Fetch data and display
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        // Access columns using array keys
        echo '<td>' . $row['rollno'] . '</td>';
        echo '<td>' . $row['fname'] ." ".$row['lname']. '</td>';
        echo '<td>' . $row['class'] . '</td>';
        echo '<td>' . $row['batch'] . '</td>';
        // Output other columns from both tables as needed
        
        // Actions column with edit and delete icons
        echo '<td>';
        echo '<a href="#" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['id'] . '"><i class="fas fa-edit"></i></a>'; // Edit icon with data-id attribute
        echo '&nbsp;';
        echo '<a href="Backend/delete.php?id=' . $row['id'] . '"><i class="fas fa-trash-alt"></i></a>'; // Delete icon with link to delete.php
        echo '</td>';
        
        echo '</tr>';
    }
    
    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    // Handle query execution error
    echo "Error: " . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
?>

<!-- Edit Student Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Student</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" action="update_student.php" method="POST">
          <!-- Form fields for editing student information -->
          <input type="hidden" id="editId" name="id"> <!-- Hidden input field to store student id -->
          <div class="mb-3">
            <label for="editRollNo" class="form-label">Roll No.</label>
            <input type="text" class="form-control" id="editRollNo" name="rollno" readonly>
          </div>
          <div class="mb-3">
            <label for="editFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editFirstName" name="fname">
          </div>
          <div class="mb-3">
            <label for="editLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editLastName" name="lname">
          </div>
          <div class="mb-3">
            <label for="editClass" class="form-label">Class</label>
            <input type="text" class="form-control" id="editClass" name="class">
          </div>
          <div class="mb-3">
            <label for="editBatch" class="form-label">Batch</label>
            <input type="text" class="form-control" id="editBatch" name="batch">
          </div>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Include DataTables.js and its dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<script>
$(document).ready(function() {
    $('#studentTable').DataTable({
        "responsive": false // Enable responsive mode
    });

    // Edit button click event handler
    $(document).on('click', 'a[data-bs-target="#editModal"]', function() {
        var id = $(this).data('id');
        // AJAX request to fetch student data
        $.ajax({
            url: 'Backend/GetStudentData.php', // Path to the PHP script
            type: 'GET',
            data: { id: id }, // Pass student ID as data
            dataType: 'json', // Expected data type
            success: function(response) {
                // Populate form fields with fetched student data
                $('#editId').val(response.id);
                $('#editRollNo').val(response.rollno);
                $('#editFirstName').val(response.fname);
                $('#editLastName').val(response.lname);
                $('#editClass').val(response.class);
                $('#editBatch').val(response.batch);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Form submission handler for updating student
    $('#editForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'Backend/UpdateStudent.php', // PHP script to update student details
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle success response
                console.log(response);
                // Close the modal after successful update
                $('#editModal').modal('hide');
                // Refresh the DataTable
                studentTable.ajax.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
