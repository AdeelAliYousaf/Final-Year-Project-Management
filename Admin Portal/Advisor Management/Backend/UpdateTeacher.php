<?php
include "db.php"; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $id = filter_var(trim($_POST['id']), FILTER_SANITIZE_NUMBER_INT);
    $FirstName = filter_var(trim($_POST['fname']), FILTER_SANITIZE_STRING);
    $LastName = filter_var(trim($_POST['lname']), FILTER_SANITIZE_STRING);
    $Department = filter_var(trim($_POST['dept']), FILTER_SANITIZE_STRING);
    $Designation = filter_var(trim($_POST['desg']), FILTER_SANITIZE_STRING);

    // Disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS=0");

    // Prepare and execute SQL statement to update teacher
    $sql = "UPDATE teacher SET fname = ?, lname = ?, dept = ?, desg = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $FirstName, $LastName, $Department, $Designation, $id);
    
    if ($stmt->execute()) {
        echo "Teacher updated successfully.";
        header('location: ../main.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not POST, output an error message
    echo "Invalid request method.";
}
?>
