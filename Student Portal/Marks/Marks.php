<?php
session_start(); // Start the session

include "../db.php"; // Include your database connection file

// Check if student ID is set in session (assuming you store it during login/authentication)
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../../index.php");
    exit(); // Stop further execution
}

// Fetch student marks or grades from your database
$query_marks = "SELECT marks FROM group_marks WHERE s_id = ?";
$stmt_marks = mysqli_prepare($conn, $query_marks);
mysqli_stmt_bind_param($stmt_marks, "i", $student_id);
mysqli_stmt_execute($stmt_marks);
$result_marks = mysqli_stmt_get_result($stmt_marks);

if (mysqli_num_rows($result_marks) > 0) {
    $marks = mysqli_fetch_assoc($result_marks)['marks'];

    // Determine grade and GPA based on marks
    $grade = '';
    $gpa = 0.00;

    if ($marks >= 85 && $marks <= 100) {
        $grade = 'A+';
        $gpa = 4.00;
    } elseif ($marks >= 80 && $marks < 85) {
        $grade = 'A';
        $gpa = 3.66;
    } elseif ($marks >= 75 && $marks < 80) {
        $grade = 'B+';
        $gpa = 3.33;
    } elseif ($marks >= 71 && $marks < 75) {
        $grade = 'B';
        $gpa = 3.00;
    } elseif ($marks >= 68 && $marks < 71) {
        $grade = 'B-';
        $gpa = 2.66;
    } elseif ($marks >= 64 && $marks < 68) {
        $grade = 'C+';
        $gpa = 2.33;
    } elseif ($marks >= 61 && $marks < 64) {
        $grade = 'C';
        $gpa = 2.00;
    } elseif ($marks >= 58 && $marks < 61) {
        $grade = 'C-';
        $gpa = 1.66;
    } elseif ($marks >= 54 && $marks < 58) {
        $grade = 'D+';
        $gpa = 1.33;
    } elseif ($marks >= 50 && $marks < 54) {
        $grade = 'D';
        $gpa = 1.00;
    } elseif ($marks < 50) {
        $grade = 'F';
        $gpa = 0.00;
    }
} else {
    $marks = '-';
    $grade = '-';
    $gpa = '-';
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Grading Details</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="marks" class="form-label">Marks</label>
                        <input type="text" class="form-control" id="marks" name="marks" readonly value="<?= htmlspecialchars($marks) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade</label>
                        <input type="text" class="form-control" id="grade" name="grade" readonly value="<?= htmlspecialchars($grade) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="gpa" class="form-label">GPA</label>
                        <input type="text" class="form-control" id="gpa" name="gpa" readonly value="<?= htmlspecialchars($gpa) ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
