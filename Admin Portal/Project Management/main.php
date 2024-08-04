<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
</head>
<body>
    <?php include "SideBar.php"; ?>

    <main>
        <br>
        <div class="container-fluid">
            <div class="row">
            </div>
            <br>
            <div class="table-responsive">
                <table id="projectsTable" class="table display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Languages</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal for project description -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="descriptionModalLabel">Project Description</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalDescriptionContent">
                    <!-- Description will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for editing project -->
    <div class="modal fade" id="editProjectModal" tabindex="-1" aria-labelledby="editProjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProjectModalLabel">Edit Project</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProjectForm">
                        <input type="hidden" id="editProjectId" name="id">
                        <div class="mb-3">
                            <label for="editProjectName" class="form-label">Project Name</label>
                            <input type="text" class="form-control" id="editProjectName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="editProjectDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="editProjectDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editProjectLanguages" class="form-label">Languages</label>
                            <input type="text" class="form-control" id="editProjectLanguages" name="lang">
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#projectsTable').DataTable({
            "responsive": true,
            "ajax": {
                "url": "Backend/FetchProjects.php",
                "dataSrc": ""
            },
            "columns": [
                { "data": "project_id" },
                { "data": "project_name" },
                { 
                    "data": "project_description",
                    "render": function(data, type, row) {
                        var words = data.split(' ');
                        if (words.length > 0) {
                            return '<a href="#" class="description-link" data-description="' + data.replace(/"/g, '&quot;') + '">View Description</a>';
                        } else {
                            return data;
                        }
                    }
                },
                { "data": "project_language" },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return '<button class="btn btn-sm btn-info edit-btn"><i class="fas fa-edit"></i> Edit</button>&nbsp;<button class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }
                }
            ]
        });

        // Handle description link click
        $('#projectsTable tbody').on('click', 'a.description-link', function(e) {
            e.preventDefault();
            var description = $(this).data('description');
            $('#modalDescriptionContent').text(description);
            $('#descriptionModal').modal('show');
        });

        // Handle edit button click using event delegation
        $('#projectsTable').on('click', 'button.edit-btn', function() {
            var $row = $(this).closest('tr');
            var rowData = table.row($row).data();
            console.log('Row Data:', rowData); // Check console for debug output
            if (rowData) {
                // Prefill the modal fields with project information
                $('#editProjectId').val(rowData.project_id);
                $('#editProjectName').val(rowData.project_name);
                $('#editProjectDescription').val(rowData.project_description);
                $('#editProjectLanguages').val(rowData.project_language);
                // Show the edit project modal
                $('#editProjectModal').modal('show');
            } else {
                console.error('Row data not found.');
            }
        });

        // Handle form submission for editing project
        $('#editProjectForm').submit(function(e) {
            e.preventDefault();
            var projectName = $('#editProjectName').val();
            var projectDescription = $('#editProjectDescription').val();
            var projectLanguages = $('#editProjectLanguages').val();
            var projectId = $('#editProjectId').val(); // Add project ID to form data

            $.post('Backend/UpdateProject.php', {
                id: projectId, // Send project ID along with other form data
                name: projectName,
                description: projectDescription,
                lang: projectLanguages
            }, function(response) {
                console.log("Response:", response); // Debugging: Log server response

                if (response && response.success) {
                    // Refresh the DataTable
                    table.ajax.reload();
                }
            }).fail(function(xhr, status, error) {
                console.error("AJAX Error:", status, error); // Log AJAX error
            });

            // Close the modal
            $('#editProjectModal').modal('hide');
            location.reload();
        });
    });

    </script>
    </body>
    </html>
