<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $FirstName = $_POST['fname'];
    $LastName = $_POST['lname'];
    $Department = $_POST['dept'];
    $Designation = $_POST['desg'];

    // Prepare and execute SQL statement to insert new teacher
    $sql_teacher = "INSERT INTO teacher (fname, lname, dept, desg) VALUES (?, ?, ?, ?)";
    $stmt_teacher = $conn->prepare($sql_teacher);
    $stmt_teacher->bind_param("ssss", $FirstName, $LastName, $Department, $Designation);
    $stmt_teacher->execute();

    // Retrieve the auto-generated teacher_id
    $teacher_id = $stmt_teacher->insert_id;

    // Generate username and password
    $username = $FirstName . '@uskt.edu.pk';
    $password = 'FYPLMS@123';

    // Prepare and execute SQL statement to insert teacher credentials
    $sql_cred = "INSERT INTO teacher_cred (t_id, email, password) VALUES (?, ?, ?)";
    $stmt_cred = $conn->prepare($sql_cred);
    $stmt_cred->bind_param("iss", $teacher_id, $username, $password);
    $stmt_cred->execute();

    // Close statements and connection
    $stmt_teacher->close();
    $stmt_cred->close();
    $conn->close();

    // Redirect or show success message
    header("Location: ../main.php?success=1");
    exit();
}
?>
