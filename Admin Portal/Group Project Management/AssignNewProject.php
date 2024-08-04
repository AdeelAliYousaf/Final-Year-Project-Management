<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign New Project to Group</title>
    
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
            <h4>Assign New Project to Group</h4>
            <div class="table-responsive">
                <table id="projectsTable" class="table display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Project ID</th>
                            <th>Project Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <br>
            <button class="btn btn-primary" id="assignBtn">
                <i class="fas fa-check"></i> Assign Selected Project
            </button>
        </div>
    </main>

    <form id="assignForm" method="post" action="Backend/AssignNewProjects.php" style="display: none;">
        <input type="hidden" name="group_id" value="<?php echo $_GET['group_id']; ?>">
        <input type="hidden" name="project_id" id="project_id">
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable for unassigned projects
            var table = $('#projectsTable').DataTable({
                "responsive": true,
                "ajax": {
                    "url": "Backend/FetchUnassignedProjects.php",
                    "dataSrc": ""
                },
                "columns": [
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<input type="radio" name="project_id" value="' + data.project_id + '">';
                        }
                    },
                    { "data": "project_id" },
                    { "data": "project_name" }
                ]
            });

            // Handle assign button
            $('#assignBtn').on('click', function() {
                var selectedProject = $('input[name="project_id"]:checked').val();
                if (selectedProject) {
                    $('#project_id').val(selectedProject);
                    $('#assignForm').submit();
                } else {
                    alert('Please select a project to assign.');
                }
            });
        });
    </script>

</body>
</html>
