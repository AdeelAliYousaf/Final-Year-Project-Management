<?php
include "db.php"; // Include your database connection file

if (isset($_GET['id'])) {
    // Retrieve and sanitize the student ID
    $id = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);

    // Disable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS=0");

    // Prepare and execute SQL statements to delete student and student_class
    $sql1 = "DELETE FROM student WHERE id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("i", $id);

    $sql2 = "DELETE FROM student_class WHERE s_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $id);

    if ($stmt1->execute() && $stmt2->execute()) {
        echo "Student deleted successfully.";
    } else {
        echo "Error: " . $stmt1->error . " " . $stmt2->error;
    }

    // Re-enable foreign key checks
    $conn->query("SET FOREIGN_KEY_CHECKS=1");

    // Close statements and connection
    $stmt1->close();
    $stmt2->close();
    $conn->close();
} else {
    // If the ID is not set, output an error message
    echo "No ID specified.";
}
?>
