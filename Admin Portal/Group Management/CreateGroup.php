<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
</head>
<body>
    <!-- Include sidebar or navigation bar -->
    <?php include "SideBar.php"; ?>

    <main>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-3">
                </div>
            </div>
            <br>
            <div class="row">
                <!-- Table for non-members of any group -->
                <div class="col-md-6">
                    <h4>Non-Members</h4>
                    <hr>
                    <br>
                    <div class="table-responsive">
                        <table id="nonMembersTable" class="table display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Batch</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Populate this table with non-members -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Table for students to be added to a new group -->
                <div class="col-md-6">
                    <h4>Students to Add</h4>
                    <hr>
                    <br>
                    <div class="table-responsive">
                        <table id="addStudentsTable" class="table display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Roll No</th>
                                    <th>Name</th>
                                    <th>Class</th>
                                    <th>Batch</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Populate this table with students to be added -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Table for students to be added to a new group -->
                <div class="col-md-6">
                    <br><br>
                    <h4>Supervisor</h4>
                    <hr>
                    <br>
                    <div class="table-responsive">
                        <table id="teacherTable" class="table display responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Teacher ID</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Populate this table with teacher to be alloted -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Button to save group and open modal -->
            <div class="row">
                <div class="col-md-12">
                    <br><br>
                    <button id="saveGroupBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#groupIDModal">Save Group</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Modal for entering group ID -->
    <div class="modal fade" id="groupIDModal" tabindex="-1" aria-labelledby="groupIDModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="groupIDModalLabel">Enter Group ID</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="groupIDForm">
                        <div class="mb-3">
                            <label for="groupID" class="form-label">Group ID</label>
                            <input type="text" class="form-control" id="groupID" name="groupID">
                            <input type="hidden" id="teacherId" name="teacherId">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add modals for adding students to a group, similar to the edit project modal -->
    <!-- JavaScript code to populate tables and handle actions -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        
        $(document).ready(function() {
            var selectedTeacherID = null;
            // Initialize DataTable for non-members
            var nonMembersTable = $('#nonMembersTable').DataTable({
                "responsive": true,
                "ajax": {
                    "url": "Backend/FetchNonMembers.php",
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "rollno" },
                    { "data": "name" },
                    { "data": "class" },
                    { "data": "batch" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-sm btn-success add-btn"><i class="fas fa-plus"></i> Add</button>';
                        }
                    }
                ]
            });

            // Initialize DataTable for students to be added
            var addStudentsTable = $('#addStudentsTable').DataTable({
                "responsive": true,
                "columns": [
                    { "data": "rollno" },
                    { "data": "name" },
                    { "data": "class" },
                    { "data": "batch" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-sm btn-danger remove-btn"><i class="fas fa-minus"></i> Remove</button>';
                        }
                    }
                ]
            });
            
            var teacherTable = $('#teacherTable').DataTable({
                "responsive": false,
                "ajax": {
                    "url": "Backend/FetchTeachers.php",
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "id" },
                    { "data": "name" },
                    { "data": "desg" },
                    { "data": "dept" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-sm btn-primary select-btn" data-id="' + row.id + '">Select</button>';
                        }
                    }
                ]
            });

            // Handle add button click
            $('#nonMembersTable tbody').on('click', 'button.add-btn', function() {
                var data = nonMembersTable.row($(this).parents('tr')).data();
                // Add the student to the second table
                addStudentsTable.row.add(data).draw();
                // Remove the row from the first table
                nonMembersTable.row($(this).parents('tr')).remove().draw();
            });

            // Handle remove button click
            $('#addStudentsTable tbody').on('click', 'button.remove-btn', function() {
                var data = addStudentsTable.row($(this).parents('tr')).data();
                // Add the student back to the first table
                nonMembersTable.row.add(data).draw();
                // Remove the row from the second table
                addStudentsTable.row($(this).parents('tr')).remove().draw();
            });

           // Handle teacher selection
            $('#teacherTable tbody').on('click', 'button.select-btn', function() {
                // Get the selected teacher ID
                selectedTeacherID = $(this).data('id');

                // Update the button color and text for visual indication
                $('#teacherTable tbody button.select-btn').removeClass('btn-primary').addClass('btn-success').text('Selected');
                $(this).removeClass('btn-success').addClass('btn-primary').text('Select');
            });

            // Handle form submission to insert group
            $('#groupIDForm').submit(function(event) {
                event.preventDefault();
                var groupID = $('#groupID').val();
                var studentIDs = [];

                // Collect student IDs from the "Students to Add" table
                addStudentsTable.rows().data().each(function(row) {
                    studentIDs.push(row.id); // Assuming "rollno" is the column containing student IDs
                });

                // Check if all required fields are set
                if (groupID && selectedTeacherID && studentIDs.length > 0) {
                    // AJAX request to insert group
                    $.ajax({
                        url: 'Backend/InsertGroup.php',
                        method: 'POST',
                        data: {
                            groupID: groupID,
                            teacherID: selectedTeacherID,
                            studentIDs: studentIDs
                            // Include other data to be sent to the backend
                        },
                        success: function(response) {
                            // Handle success response from the backend
                            console.log(response);
                            // Close the modal
                            $('#groupIDModal').modal('hide');
                        },
                        error: function(xhr, status, error) {
                            // Handle error response from the backend
                            console.error(xhr.responseText);
                        }
                    });
                } else {
                    // Display an error message if any required field is not set
                    console.error("Required fields are not set.");
                }
            });
        });
    </script>
</body>
</html>
