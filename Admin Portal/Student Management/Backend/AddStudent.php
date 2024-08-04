<?php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $FirstName = $_POST['fname'];
    $LastName = $_POST['lname'];
    $DOB = $_POST['dob'];
    $Email = $_POST['email'];
    $Phone = $_POST['phone'];
    $Class = $_POST['class'];
    $Batch = $_POST['batch'];
    $Rollno = $_POST['rollno'];

    // Calculate age based on date of birth
    $dob_timestamp = strtotime($DOB);
    $current_timestamp = time();
    $age = date('Y', $current_timestamp) - date('Y', $dob_timestamp);
    if (date('md', $current_timestamp) < date('md', $dob_timestamp)) {
        $age--;
    }

    // Prepare and execute SQL statement to insert new student
    $sql_student = "INSERT INTO student (fname, lname, dob, age, email, phone) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt_student = $conn->prepare($sql_student);
    $stmt_student->bind_param("sssiss", $FirstName, $LastName, $DOB, $age, $Email, $Phone);
    $stmt_student->execute();

    // Retrieve the auto-generated student_id
    $student_id = $stmt_student->insert_id;

    // Prepare and execute SQL statement to insert student class details
    $sql_studentclass = "INSERT INTO student_class (s_id, rollno, class, batch) VALUES (?, ?, ?, ?)";
    $stmt_studentclass = $conn->prepare($sql_studentclass);
    $stmt_studentclass->bind_param("isss", $student_id, $Rollno, $Class, $Batch);
    $stmt_studentclass->execute();

    // Generate username and password
    $username = $Rollno;
    $password = 'FYPLMS@123';

    // Prepare and execute SQL statement to insert student credentials
    $sql_cred = "INSERT INTO student_cred (s_id, username, password) VALUES (?, ?, ?)";
    $stmt_cred = $conn->prepare($sql_cred);
    $stmt_cred->bind_param("iss", $student_id, $username, $password); // Note: Use $Email for email
    $stmt_cred->execute();

    // Close statements and connection
    $stmt_student->close();
    $stmt_studentclass->close();
    $stmt_cred->close();
    $conn->close();

    // Redirect or show success message
    header("Location: ../main.php?success=1");
    exit();
}
?>
