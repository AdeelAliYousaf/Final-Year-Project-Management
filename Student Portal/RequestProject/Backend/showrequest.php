<?php
session_start();
include "db.php";

// Check if student ID is set in session
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page if student ID is not set
    header("Location: ../../index.php");
    exit();
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
$request = mysqli_query($conn, $sql2);

if (mysqli_num_rows($request) > 0) {
    $row = mysqli_fetch_array($request);
    $id = $row['id'];
    $title = $row['title'];
    $description = $row['description'];
    $lang = $row['lang'];
} else {
    echo "<script>
        alert('No project requests found.');
        window.location.href = '../StudentDashboard.php';
    </script>";
    mysqli_close($conn);
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Previous Requests</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include("../navbar.php"); ?>

    <div class="container mt-4">
        <button class="btn btn-primary" onclick="handleClick()">Go Back</button>
        <h2 class="text-center mb-4">Previous Project Requests</h2>
        <table class="table table-striped table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Languages</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($id); ?></td>
                    <td><?php echo htmlspecialchars($title); ?></td>
                    <td><?php echo htmlspecialchars($description); ?></td>
                    <td><?php echo htmlspecialchars($lang); ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleClick()
        {
            history.back();
        }
    </script>
</body>
</html>
