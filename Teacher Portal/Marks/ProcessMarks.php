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

// Ensure form data is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $group_id = $_POST['group_id'];
    $marks = $_POST['marks'];

    foreach ($marks as $rollno => $mark) {
        // Fetch student ID (s_id) using rollno
        $fetch_student_id_query = "SELECT s_id FROM student_class WHERE rollno = ?";
        $stmt = mysqli_prepare($conn, $fetch_student_id_query);
        mysqli_stmt_bind_param($stmt, "s", $rollno);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $student_id = $row['s_id'];

        // Insert or update marks
        $insert_query = "INSERT INTO group_marks (g_id, s_id, marks, remarks, uploaded_at)
                         VALUES ('$group_id', '$student_id', '$mark', NULL, NOW())
                         ON DUPLICATE KEY UPDATE marks = VALUES(marks), remarks = VALUES(remarks), uploaded_at = VALUES(uploaded_at)";

        if (mysqli_query($conn, $insert_query)) {
            // Marks inserted or updated successfully
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($conn);
        }
    }

    // Close MySQL connection
    mysqli_close($conn);

    // Redirect to a success page or back to upload marks page
    header("Location: UploadMarks.php");
    exit();
} else {
    // Redirect to upload marks page if accessed directly without POST data
    header("Location: UploadMarksForm.php");
    exit();
}
?>
