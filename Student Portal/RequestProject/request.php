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

$sql_query = "SELECT * FROM groups_student WHERE s_id = $student_id";

$result = mysqli_query($conn, $sql_query);
$showRequest = false;

if (mysqli_num_rows($result) > 0) {
    $showRequest = false; // Student is in a group, show the form
} else {
    $showRequest = true;  // No group found, show the alert message
}

$sql1 = "SELECT g_id FROM groups_student WHERE s_id = '$student_id'";
$groupresult = mysqli_query($conn, $sql1);

if (mysqli_num_rows($groupresult) > 0) {
    $row = mysqli_fetch_array($groupresult);
    $group = $row['g_id'];
} else {
    echo "<script>
        alert('You are not assigned to any group.');
        window.location.href = '../StudentDashboard.php';
    </script>";
    mysqli_close($conn);
    exit();
}
$sql2 = "SELECT * FROM temp_projects WHERE g_id = '$group'";
$request = mysqli_query($conn,$sql2);
$showreuquestbtn = false;
if(mysqli_num_rows($request) > 0){
    $showreuquestbtn = true;
}
else{
    $showreuquestbtn = false;
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
        *{
            
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body>
    <?php include("navbar.php"); ?>

    <div class="container mt-4">
        <?php if ($showRequest): ?>
            <div class="alert alert-warning text-center" role="alert">
                <h3>To Request a Project from Your Advisor, You Must be in a Group</h3>
                <h6>No Group Found!</h6>
            </div>
        <?php else: ?>
            <!-- Responsive Form -->
            <h3 class="text-center mb-4">Request a Project</h3>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="Backend/SubmitProjectRequest.php" method="post">
                        <div class="mb-3">
                            <label for="projectTitle" class="form-label">Project Title</label>
                            <input type="text" class="form-control" id="projectTitle" name="title" required placeholder="Project Name">
                        </div>
                        <div class="mb-3">
                            <label for="projectDescription" class="form-label">Project Description</label>
                            <textarea class="form-control" id="projectDescription" name="description" rows="4" required placeholder="Details about Project"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="projectDetails" class="form-label">Additional Details</label>
                            <textarea class="form-control" id="projectDetails" name="lang" rows="4" placeholder="Languages that will be used e.g., PHP, Python etc.,"></textarea>
                        </div>
                        <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Submit Requests</button>
                        </div>
                    </form>
                    <?php if($showreuquestbtn): ?>
                        <form action="Backend/showrequest.php" method="GET">
                            <div class="mb-3">
                                <button type="submit" class="btn btn-warning">Show Previous Requests</button>
                            </div>
                        </form>
                        
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
