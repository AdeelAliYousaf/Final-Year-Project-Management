<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Project to Group</title>
    
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
            <form id="assignForm" method="post" action="Backend/AssignProjectToGroup.php">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Unassigned Groups</h4>
                        <div class="table-responsive">
                            <table id="groupsTable" class="table display responsive nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Select</th>
                                        <th>Group ID</th>
                                        <th>Teacher Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h4>Unassigned Projects</h4>
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
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check"></i> Assign Selected Projects to Selected Groups
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable for unassigned groups
            var groupsTable = $('#groupsTable').DataTable({
                "responsive": true,
                "ajax": {
                    "url": "Backend/FetchUnassignedGroups.php",
                    "dataSrc": ""
                },
                "columns": [
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<input type="radio" name="group_ids[]" value="' + data.group_id + '">';
                        }
                    },
                    { "data": "group_id" },
                    { "data": "teacher_name" }
                ]
            });

            // Initialize DataTable for unassigned projects
            var projectsTable = $('#projectsTable').DataTable({
                "responsive": true,
                "ajax": {
                    "url": "Backend/FetchUnassignedProjects.php",
                    "dataSrc": ""
                },
                "columns": [
                    {
                        "data": null,
                        "render": function(data, type, row) {
                            return '<input type="radio" name="project_ids[]" value="' + data.project_id + '">';
                        }
                    },
                    { "data": "project_id" },
                    { "data": "project_name" }
                ]
            });
        });
    </script>

</body>
</html>
