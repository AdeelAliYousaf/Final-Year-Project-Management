<?php
session_start(); // Start the session

include "../db.php";

// Check if teacher ID is set in session (assuming you store it during login/authentication)
if (isset($_SESSION['id'])) {
    $teacher_id = $_SESSION['id'];
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
    <title>Teacher Groups</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
        *{
            
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="../TeacherDashboard.php">Teacher Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../TeacherDashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Marks/UploadMarks.php">Upload Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Project Management/main.php">Old Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Notification/SendMessage.php">Send Message</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link" href="../Logout/logout.php" style="color: red;"><i class="fas fa-power-off"></i>Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Assigned Groups</h2>
    <div class="row">
        <div class="col-md-12">
            <table id="groupsTable" class="table table-striped table-bordered responsive">
                <thead>
                    <tr>
                        <th>Group ID</th>
                        <th>Supervisor</th>
                        <th>Project</th>
                        <th>Members</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../db.php";

                    // Query to fetch groups, members, and projects assigned to the teacher
                    $query = "SELECT groups.id AS group_id, 
                                      CONCAT(teacher.fname, ' ', teacher.lname) AS supervisor_name,
                                      GROUP_CONCAT(DISTINCT project.name SEPARATOR '<br>') AS projects,
                                      GROUP_CONCAT(DISTINCT CONCAT(student.fname, ' ', student.lname, ' - ', student_class.class, ', Batch: ', student_class.batch, ', Roll No: ', student_class.rollno) SEPARATOR '<br>') AS members_info
                              FROM groups 
                              INNER JOIN teacher ON groups.t_id = teacher.id 
                              LEFT JOIN groups_student ON groups.id = groups_student.g_id
                              LEFT JOIN student ON groups_student.s_id = student.id
                              LEFT JOIN student_class ON student.id = student_class.s_id
                              LEFT JOIN groups_project ON groups.id = groups_project.g_id
                              LEFT JOIN project ON groups_project.p_id = project.id
                              WHERE groups.t_id = $teacher_id
                              GROUP BY groups.id";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($row['group_id']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['supervisor_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['projects']) . '</td>';
                            echo '<td>';
                            echo '<ol>';
                            $members_list = explode('<br>', $row['members_info']);
                            foreach ($members_list as $member) {
                                echo '<li>' . htmlspecialchars($member) . '</li>';
                            }
                            echo '</ol>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No groups assigned.</td></tr>';
                    }

                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
    $(document).ready(function() {
        $('#groupsTable').DataTable({
            responsive: true
        });
    });
</script>
</body>
</html>
