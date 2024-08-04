<?php
include "Backend/db.php";

// Check if student ID is set in session
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page if student ID is not set
    header("Location: ../../index.php");
    exit();
}

// Fetch group project details
$sql_query = "SELECT 
                groups_project.*, 
                groups_student.*,
                groups.*, 
                student.id AS student_id
              FROM 
                groups_project
              INNER JOIN 
                groups ON groups.id = groups_project.g_id
              INNER JOIN
                groups_student ON groups_student.g_id = groups.id
              INNER JOIN 
                student ON student.id = groups_student.s_id
              WHERE
                student.id = $student_id";

$result = mysqli_query($conn, $sql_query);
$showRequestProjectLink = false;

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $sid = $row['student_id'];
        if ($sid == $student_id) {
            $showRequestProjectLink = true;
            break;
        }
    }
}
mysqli_close($conn);
?>

<head>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Student Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../StudentDashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Groups/main.php">Group Details</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Marks/Marks.php">FYP Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Project Management/main.php">Old Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Notification/SendMessage.php">Send Message</a>
                </li>
                <?php if (!$showRequestProjectLink): ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../RequestProject/request.php">⭐Request Project⭐</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" style="color: red;" href="../Logout/logout.php"><i class="fas fa-power-off"></i>
                    Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
