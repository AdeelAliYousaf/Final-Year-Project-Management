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

// Ensure group_id is provided through GET parameter
if (!isset($_GET['group_id'])) {
    echo "Group ID is not provided.";
    exit();
}

$group_id = $_GET['group_id'];

// Sanitize group_id parameter
$group_id = mysqli_real_escape_string($conn, $group_id);


// Query to fetch group members using prepared statement
$query = "SELECT student_class.rollno, student.fname, student.lname, student_class.class, student_class.batch
          FROM student
          INNER JOIN student_class ON student.id = student_class.s_id
          INNER JOIN groups_student ON student_class.s_id = groups_student.s_id
          WHERE groups_student.g_id = ?";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $group_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $group_members = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $group_members[] = $row;
    }
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
    exit();
}

// Check if group exists
if (empty($group_members)) {
    echo "Group not found.";
    exit();
}

// Fetch group details (using the first member's details)
$group_details = $group_members[0];

// Close MySQL connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Marks</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="TeacherDashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="TeacherGroups.php">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">Upload Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Project Management/main.php">Old Projects</a>
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
    <h2>Upload Marks for Group <?php echo htmlspecialchars($group_id); ?></h2>
    <h3>Group Details</h3>
    <div>
        <p><strong>Group ID:</strong> <?php echo htmlspecialchars($group_id); ?></p>
        <p><strong>Class:</strong> <?php echo htmlspecialchars($group_details['class']); ?></p>
        <p><strong>Batch:</strong> <?php echo htmlspecialchars($group_details['batch']); ?></p>
    </div>

    <h3>Group Members</h3>
    <form action="ProcessMarks.php" method="post">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Roll No</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Batch</th>
                    <th>Marks (out of 100)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($group_members as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['rollno']); ?></td>
                        <td><?php echo htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                        <td><?php echo htmlspecialchars($row['class']); ?></td>
                        <td><?php echo htmlspecialchars($row['batch']); ?></td>
                        <td>
                            <input type="number" name="marks[<?php echo htmlspecialchars($row['rollno']); ?>]" class="form-control"
                                   value="<?php echo isset($existing_marks[$row['rollno']]) ? htmlspecialchars($existing_marks[$row['rollno']]) : ''; ?>"
                                   min="0" max="100" required>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <input type="hidden" name="group_id" value="<?php echo htmlspecialchars($group_id); ?>">
        <button type="submit" class="btn btn-primary">Submit Marks</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
