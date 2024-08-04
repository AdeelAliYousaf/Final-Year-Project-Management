<?php
include "db.php"; // Include your database connection file

if (isset($_GET['id'])) {
    // Retrieve and sanitize the teacher ID
    $id = filter_var(trim($_GET['id']), FILTER_SANITIZE_NUMBER_INT);

    // Check if the teacher is a supervisor in any group
    $checkQuery = "SELECT * FROM groups WHERE t_id = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param("i", $id);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        // Teacher is a supervisor in a group, display an error message
        echo "<script>
                alert('Cannot delete teacher. They are a supervisor of a group.');
                window.location.href = '../main.php';
              </script>";
    } else {
        // Disable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=0");

        // Prepare and execute SQL statement to delete teacher
        $sql = "DELETE FROM teacher WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Teacher deleted successfully.');
                    window.location.href = '../main.php';
                  </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Re-enable foreign key checks
        $conn->query("SET FOREIGN_KEY_CHECKS=1");

        // Close statement and connection
        $stmt->close();
    }

    // Close connection
    $checkStmt->close();
    $conn->close();
} else {
    // If the ID is not set, output an error message
    echo "No ID specified.";
}
?>
