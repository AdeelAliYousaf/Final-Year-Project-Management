<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <style>
        .table-body{
            cursor: pointer;
            
        }
        .table-body:hover{
            text-decoration: underline;
            color: blue;
        }
    </style>
</head>
<body>
    <?php include "SideBar.php"; ?>

    <main>
        <br>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <button class="btn btn-primary" onclick="location.href='CreateGroup.php'">
                        <i class="fas fa-plus"></i> Create Group
                    </button>
                </div>
            </div>
            <br>
            <div class="table-responsive">
            <table id="groupsTable" class="table">
                <thead>
                    <tr>
                        <th>Group ID</th>
                        <th>Supervisor</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="table-body">
                </tbody>
            </table>
            </div>
        </div>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#groupsTable').DataTable({
            "responsive": true,
            "ajax": {
                "url": "Backend/FetchGroups.php",
                "dataSrc": ""
            },
            "columns": [
                { "data": "group_id" },
                { "data": "teacher_name" },
                { 
                    "data": null,
                    "render": function(data, type, row) {
                        return '<button class="btn btn-sm btn-info edit-btn" data-id="' + row.group_id + '"><i class="fas fa-edit"></i> Edit</button>&nbsp; <p> </p><button class="btn btn-sm btn-danger delete-btn" data-id="' + row.group_id + '"><i class="fas fa-trash-alt"></i> Delete</button>';
                    }
                }
            ]
        });

        // Edit button click event
        $('#groupsTable tbody').on('click', '.edit-btn', function() {
            var data = table.row($(this).parents('tr')).data();
            window.location.href = "Backend/EditGroup.php?group_id=" + data.group_id;
        });

        // Handle row click event for redirection to group details
        $('#groupsTable tbody').on('click', 'tr', function (e) {
            if (!$(e.target).hasClass('edit-btn') && !$(e.target).hasClass('delete-btn')) {
                var data = table.row(this).data();
                window.location.href = "Backend/GroupDetails.php?group_id=" + data.group_id;
            }
        });
    });
</script>
