<?php
include "db.php"; // Include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input values
    $id = filter_var(trim($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
    $rollno = filter_var(trim($_POST['rollno']), FILTER_SANITIZE_STRING);
    $fname = filter_var(trim($_POST['fname']), FILTER_SANITIZE_STRING);
    $lname = filter_var(trim($_POST['lname']), FILTER_SANITIZE_STRING);
    $class = filter_var(trim($_POST['class']), FILTER_SANITIZE_STRING);
    $batch = filter_var(trim($_POST['batch']), FILTER_SANITIZE_STRING);

    // Prepare and execute SQL statement to update student details
    $sql = "UPDATE student 
            INNER JOIN student_class ON student.id = student_class.s_id
            SET student_class.rollno = ?, student.fname = ?, student.lname = ?, student_class.class = ?, student_class.batch = ?
            WHERE student.id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $rollno, $fname, $lname, $class, $batch, $id);
    
    if ($stmt->execute()) {
        echo "Student updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>
