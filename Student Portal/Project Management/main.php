<?php
session_start();
include "Backend/db.php";

// Check if student ID is set in session
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page if student ID is not set
    header("Location: ../../index.php");
    exit();
}
?>
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
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
        *{
            
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body>
    
<?php include("navbar.php"); ?>


    <main>
        <br>
        <div class="container-fluid">

            <br>
            <div class="table-responsive">
                <table id="projectsTable" class="table display responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Languages</th>
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
                { "data": "project_language" }
            ]
        });

        // Handle description link click
        $('#projectsTable tbody').on('click', 'a.description-link', function(e) {
            e.preventDefault();
            var description = $(this).data('description');
            $('#modalDescriptionContent').text(description);
            $('#descriptionModal').modal('show');
        });
    });

    </script>
    </body>
    </html>
