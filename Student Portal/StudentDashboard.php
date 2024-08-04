<?php
session_start(); // Start the session

include "db.php";

// Check if student ID is set in session (assuming you store it during login/authentication)
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../../index.php");
    exit(); // Stop further execution
}

// Fetch student details including class and batch
$query_student = "SELECT * FROM student_class
                  INNER JOIN student ON student.id = student_class.s_id
                  WHERE student.id = $student_id";
$result_student = mysqli_query($conn, $query_student);

// Fetch student credentials
$query_credentials = "SELECT * FROM student_cred WHERE s_id = $student_id";
$result_credentials = mysqli_query($conn, $query_credentials);

// Check if student exists and has credentials
if (mysqli_num_rows($result_student) > 0 && mysqli_num_rows($result_credentials) > 0) {
    $student_info = mysqli_fetch_assoc($result_student);
    $student_credentials = mysqli_fetch_assoc($result_credentials);

    // Check if student is using the default password
    if ($student_credentials['password'] === 'FYPLMS@123') {
        // Redirect to password change page
        header("Location: ChangePassword.php");
        exit();
    }
} else {
    echo "<p>No student found.</p>";
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
<?php
    include ("navbar.php");
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Personal Information</h5>
                </div>
                <div class="card-body">
                    <?php
                    // Display student information
                    if (mysqli_num_rows($result_student) > 0) {
                        ?>
                        <div class="mb-3">
                            <label for="name" class="form-label">Rollno</label>
                            <input type="text" class="form-control" id="name" name="rollno" readonly value="<?= htmlspecialchars($student_info['rollno']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" readonly value="<?= htmlspecialchars($student_info['fname'] . ' ' . $student_info['lname']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="class" class="form-label">Class</label>
                            <input type="text" class="form-control" id="class" name="class" readonly value="<?= htmlspecialchars($student_info['class']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="batch" class="form-label">Batch</label>
                            <input type="text" class="form-control" id="batch" name="batch" readonly value="<?= htmlspecialchars($student_info['batch']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label">DOB</label>
                            <input type="date" class="form-control" id="dob" name="dob" readonly value="<?= htmlspecialchars($student_info['dob']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Age</label>
                            <input type="number" class="form-control" id="age" name="age" readonly value="<?= htmlspecialchars($student_info['age']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" readonly value="<?= htmlspecialchars($student_info['email']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" readonly value="<?= htmlspecialchars($student_info['phone']) ?>">
                        </div>
                        <?php
                    } else {
                        echo "<p>No student found.</p>";
                    }
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
                        $query = "  SELECT CONCAT(teacher.fname, ' ', teacher.lname) AS name, groups.id AS g_id, student_notification.message
                                    FROM groups_student
                                    INNER JOIN groups ON groups.id = groups_student.g_id
                                    INNER JOIN teacher ON groups.t_id = teacher.id
                                    INNER JOIN student_notification ON groups_student.s_id = student_notification.s_id
                                    WHERE groups_student.s_id = '$student_id'
                                    ORDER BY student_notification.id DESC;  ";
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<a href="#" class="list-group-item list-group-item-action">';
                            echo '<div class="d-flex w-100 justify-content-between">';
                            echo '<h5 class="list-group-heading">' . htmlspecialchars($row['name']) . '</h5>';
                            echo '<small>' . htmlspecialchars($row['g_id']) . '</small>';
                            echo '</div>';
                            echo '<p class="mb-1">' . htmlspecialchars($row['message']) . '</p>';
                            echo '</a>';
                        }
                        mysqli_close($conn);
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Other content as per your layout -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
