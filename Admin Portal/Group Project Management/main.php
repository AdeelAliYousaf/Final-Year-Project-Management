<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Group Projects</title>
    
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
                <div class="col-md-12 mb-3">
                    <button class="btn btn-primary" onclick="location.href='AssignProjectToGroups.php'">
                        <i class="fas fa-plus"></i> Assign Project to Group
                    </button>
                </div>
            </div>
            <br>
            <div class="table-responsive">
                <table id="groupProjectsTable" class="table display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Group ID</th>
                            <th>Teacher Name</th>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <form id="unassignForm" method="post" action="Backend/UnassignProject.php" style="display: none;">
        <input type="hidden" name="group_id" id="group_id">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#groupProjectsTable').DataTable({
                "responsive": true,
                "ajax": {
                    "url": "Backend/FetchGroupProjects.php",
                    "dataSrc": ""
                },
                "columns": [
                    { "data": "group_id" },
                    { "data": "teacher_name" },
                    { "data": "project_id" },
                    { "data": "project_name" },
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<button class="btn btn-sm btn-info edit-btn"><i class="fas fa-edit"></i> Edit</button>&nbsp;<button class="btn btn-sm btn-danger delete-btn"><i class="fas fa-trash-alt"></i> Delete</button>';
                        }
                    }
                ]
            });

            // Handle edit button
            $('#groupProjectsTable tbody').on('click', 'button.edit-btn', function() {
                var data = table.row($(this).parents('tr')).data();
                $('#group_id').val(data.group_id);
                $('#unassignForm').submit();
            });

            // Handle delete button (you can add your own functionality here)
            $('#groupProjectsTable tbody').on('click', 'button.delete-btn', function() {
                var data = table.row($(this).parents('tr')).data();
                // Your delete logic here
                alert('Delete group project: ' + data.group_id + ' - ' + data.project_name);
            });
        });
    </script>

</body>
</html>
