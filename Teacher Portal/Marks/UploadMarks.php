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

// Query to fetch assigned groups with marks status
$query = "SELECT groups.id AS group_id, CONCAT(teacher.fname, ' ', teacher.lname) AS supervisor,
                  CASE WHEN group_marks.g_id IS NOT NULL THEN 'Uploaded' ELSE 'Pending' END AS marks_status
          FROM groups
          INNER JOIN teacher ON groups.t_id = teacher.id
          LEFT JOIN group_marks ON groups.id = group_marks.g_id AND group_marks.s_id = ?
          WHERE groups.t_id = ?"; // Filter by teacher ID

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "ii", $teacher_id, $teacher_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Marks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        <a class="navbar-brand" href="#">Teacher Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                    <a class="nav-link" href="../TeacherDashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Groups/main.php">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../Marks/UploadMarks.php">Upload Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Project Management/main.php">Old Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link "  href="../Notification/SendMessage.php">Send Message</a>
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
    <h2>Group Marks Status</h2>
    <table id="groupTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Group ID</th>
                <th>Supervisor</th>
                <th>Marks Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- PHP code to populate table rows -->
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['group_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['supervisor']) . '</td>';
                    echo '<td>';
                    echo '<span class="badge bg-' . ($row['marks_status'] == 'Uploaded' ? 'success">Uploaded' : 'warning">Pending') . '</span>';
                    echo '</td>';
                    echo '<td><a href="UploadMarksForm.php?group_id=' . $row['group_id'] . '" class="btn btn-primary btn-sm">Upload Marks</a></td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="5">No groups assigned.</td></tr>';
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            ?>
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and DataTables JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#groupTable').DataTable();
    });
</script>

</body>
</html>
