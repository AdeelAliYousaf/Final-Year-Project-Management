<?php
session_start(); // Start the session

include "db.php";

// Check if student ID is set in session (assuming you store it during login/authentication)
if (isset($_SESSION['id'])) {
    $teacherid = $_SESSION['id'];
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../../index.php");
    exit(); // Stop further execution
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
        *{
            
            font-family: "Poppins", sans-serif;
        }
        .card {
            height: 100%;
        }
        .card-body {
            overflow-y: auto; /* Enable vertical scroll if content exceeds height */
            max-height: 400px; /* Limit height to 400px */
        }
        .heading{
            animation: blink 1s infinite linear;
        }
        @keyframes blink{
            0%, 100%{
                opacity: 1;
                color: orange;
            }
            50%{
                opacity: 0;
                color: blue;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Teacher Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Groups/main.php">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Marks/UploadMarks.php">Upload Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Project Management/main.php">Old Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Notification/SendMessage.php">Send Message</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Logout/logout.php" style="color: red;"><i class="fas fa-power-off"></i>Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Personal Information</h5>
                </div>
                <div class="card-body">
                    <?php
                    include "db.php";
                    // Assuming teacher ID is obtained from session or login
                    $teacher_id = $teacherid; // Replace with actual logic to get teacher ID
                    $query = "SELECT * FROM teacher WHERE id = $teacher_id";
                    $result = mysqli_query($conn, $query);
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                    ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" readonly value="<?= htmlspecialchars($row['fname'] . ' ' . $row['lname']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="dept" class="form-label">Department</label>
                            <input type="text" class="form-control" id="dept" name="dept" readonly value="<?= htmlspecialchars($row['dept']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="desg" class="form-label">Designation</label>
                            <input type="text" class="form-control" id="desg" name="desg" readonly value="<?= htmlspecialchars($row['desg']) ?>">
                        </div>
                    <?php
                    } else {
                        echo "<p>No teacher found.</p>";
                    }
                    mysqli_close($conn);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Notifications</h5>
                </div>
                <div class="card-body">
                <div class="list-group">
                    <!-- PHP code to fetch and display notifications -->
                    <?php
                    include "db.php";
                    $query = "SELECT CONCAT(student.fname, ' ', student.lname) AS name,
                              groups_student.g_id AS g_id,
                              teacher_notification.message AS message,
                              teacher_notification.attachment AS attachment
                              FROM groups
                              INNER JOIN groups_student ON groups_student.g_id = groups.id
                              INNER JOIN student ON groups_student.s_id = student.id
                              INNER JOIN teacher_notification ON groups.id = teacher_notification.g_id
                              WHERE groups.t_id = '$teacherid'
                              ORDER BY teacher_notification.id DESC;";

                    $result = mysqli_query($conn, $query);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<div class="card mb-3">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">Student Name: ' . htmlspecialchars($row['name']) . '</h5>';
                        echo '<h6 class="card-subtitle mb-2 text-muted">Group ID: ' . htmlspecialchars($row['g_id']) . '</h6>';
                        echo '<p class="card-text"><b>Message: </b>' . htmlspecialchars($row['message']) . '</p>';
                        if (!empty($row['attachment'])) {
                            $attachment_url = '../Student Portal/Notification/' . htmlspecialchars($row['attachment']);
                            echo '<a class="btn btn-primary" href="' . $attachment_url . '" target="_blank">View Attachment</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }
                    
                    // Fetch project requests
                    $sql_query = "SELECT * FROM temp_projects WHERE t_id = '$teacherid'";
                    $result1 = mysqli_query($conn, $sql_query);
                    while ($row = mysqli_fetch_assoc($result1)) {
                        echo '<a href="#" class="list-group-item list-group-item-action" data-bs-toggle="modal" data-bs-target="#projectModal" 
                            data-id="' . htmlspecialchars($row['id']) . '" 
                            data-title="' . htmlspecialchars($row['title']) . '" 
                            data-description="' . htmlspecialchars($row['description']) . '" 
                            data-lang="' . htmlspecialchars($row['lang']) . '">';
                        echo '<div class="d-flex w-100 justify-content-between">';
                        echo '<h5 class="mb-1 heading">Project Request</h5>';
                        echo '<small>' . htmlspecialchars($row['g_id']) . '</small>';
                        echo '</div>';
                        echo '<p><b>Title: </b>' . htmlspecialchars($row['title']) . '</p>';
                        echo '</a>';
                        $status = $row['status'];
                        if($status == "accepted")
                        {
                            $status_bool = true;
                        }
                        else{
                            $status_bool = false;
                        }
                    }
                    
                    mysqli_close($conn);
                    ?>
                </div>

            </div>

            </div>
        </div>
    </div>
</div>
<!-- Modal for project details -->
<div class="modal fade" id="projectModal" tabindex="-1" aria-labelledby="projectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectModalLabel">Project Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Request ID:</strong> <span id="modalGroupId"></span></p>
                <p><strong>Title:</strong> <span id="modalProjectTitle"></span></p>
                <p><strong>Description:</strong> <span id="modalProjectDescription"></span></p>
                <p><strong>Languages:</strong> <span id="modalProjectLang"></span></p>
                <input type="hidden" id="modalProjectId">
            </div>
            <?php if($status_bool == false): ?>
            <div class="modal-footer">
                <form id="acceptForm" method="post" action="handle_request.php" style="display:inline;">
                    <input type="hidden" name="request_id" id="acceptRequestId">
                    <button type="submit" name="action" value="accept" class="btn btn-success">Accept</button>
                </form>
                <form id="rejectForm" method="post" action="handle_request.php" style="display:inline;">
                    <input type="hidden" name="request_id" id="rejectRequestId">
                    <button type="submit" name="action" value="reject" class="btn btn-danger">Reject</button>
                </form>
            </div>
            <?php else: ?>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">You Have Accepted this Project Request</button>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    $('#projectModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var gid = button.data('id');
        var pid = button.data('id');
        var id = button.data('id');
        var title = button.data('title');
        var description = button.data('description');
        var lang = button.data('lang');
        
        var modal = $(this);
        modal.find('#modalGroupId').text(gid);
        modal.find('#modalProjectId').val(pid);
        modal.find('#modalProjectTitle').text(title);
        modal.find('#modalProjectDescription').text(description);
        modal.find('#modalProjectLang').text(lang);
        modal.find('#acceptRequestId').val(id);
        modal.find('#rejectRequestId').val(id);
    });
});
</script>

</body>
</html>
