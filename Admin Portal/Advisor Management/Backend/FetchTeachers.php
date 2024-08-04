<?php
include "db.php";

// Function to get student details by ID
function getAdvisor($id) {
    global $conn;
    $query = "SELECT * FROM teacher WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// SQL query to fetch data from the teacher table
$query = "SELECT * FROM teacher";

// Execute the query
$result = mysqli_query($conn, $query);

// Check if query executed successfully
if ($result) {
    echo '<div class="table-responsive">';
    echo '<table id="teacherTable" class="table">';
    echo '<thead class="thead-dark">';
    echo '<tr>';
    echo '<th scope="col">ID</th>';
    echo '<th scope="col">First Name</th>';
    echo '<th scope="col">Last Name</th>';
    echo '<th scope="col">Department</th>';
    echo '<th scope="col">Designation</th>';
    echo '<th scope="col">Actions</th>'; // New column for actions
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    // Fetch data and display
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        // Access columns using array keys
        echo '<td>' . $row['id'] . '</td>';
        echo '<td>' . $row['fname'] . '</td>';
        echo '<td>' . $row['lname'] . '</td>';
        echo '<td>' . $row['dept'] . '</td>';
        echo '<td>' . $row['desg'] . '</td>';
        
        echo '<td>';
        echo '<a href="#" class="edit-btn" data-bs-toggle="modal" data-bs-target="#editModal" data-id="' . $row['id'] . '"><i class="fas fa-edit"></i></a>'; // Edit icon with data-id attribute
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

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Advisor</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editForm" action="update_teacher.php" method="POST">
          <!-- Form fields for editing teacher information -->
          <input type="hidden" id="editId" name="id"> <!-- Hidden input field to store teacher id -->
          <div class="mb-3">
            <label for="editFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="editFirstName" name="fname">
          </div>
          <div class="mb-3">
            <label for="editLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="editLastName" name="lname">
          </div>
          <div class="mb-3">
            <label for="editDept" class="form-label">Department</label>
            <input type="text" class="form-control" id="editDept" name="dept">
          </div>
          <div class="mb-3">
            <label for="editDesg" class="form-label">Designation</label>
            <input type="text" class="form-control" id="editDesg" name="desg">
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
    $('#teacherTable').DataTable({
        responsive: false // Enable responsive mode
    });

    // Edit button click event handler
    $('.edit-btn').click(function() {
        var id = $(this).data('id');
        // AJAX request to fetch teacher data
        $.ajax({
            url: 'Backend/GetTeacherData.php', // Path to the PHP script
            type: 'GET',
            data: { id: id }, // Pass teacher ID as data
            dataType: 'json', // Expected data type
            success: function(response) {
                // Populate form fields with fetched teacher data
                $('#editId').val(response.id);
                $('#editFirstName').val(response.fname);
                $('#editLastName').val(response.lname);
                $('#editDept').val(response.dept);
                $('#editDesg').val(response.desg);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

    // Form submission handler for updating teacher
    $('#editForm').submit(function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            url: 'Backend/UpdateTeacher.php', // PHP script to update teacher details
            type: 'POST',
            data: formData,
            success: function(response) {
                // Handle success response
                console.log(response);
                // Close the modal after successful update
                $('#editModal').modal('hide');
                // Refresh the DataTable
                $('#teacherTable').DataTable().ajax.reload();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
