<?php
include "db.php"; // Include your database connection file

// Check if group_id and student_id are provided via POST
if (isset($_POST['group_id']) && isset($_POST['student_id'])) {
    $group_id = $_POST['group_id'];
    $student_id = $_POST['student_id'];

    // Delete the student from groups_student table
    $query = "DELETE FROM groups_student WHERE g_id = ? AND s_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $group_id, $student_id);
        mysqli_stmt_execute($stmt);
        
        // Check if deletion was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Member removed successfully.";
        } else {
            echo "Failed to remove member.";
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}

mysqli_close($conn);
?>
