<?php
include "db.php"; // Include your database connection file

// Check if group_id and supervisor are provided via POST
if (isset($_POST['group_id']) && isset($_POST['t_id'])) {
    $group_id = $_POST['group_id'];
    $supervisor_id = $_POST['t_id'];

    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    // Update the group table with new supervisor
    $query = "UPDATE groups SET t_id = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "si", $supervisor_id, $group_id);
        mysqli_stmt_execute($stmt);
        
        // Check if update was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Supervisor updated successfully.";
            $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        } else {
            echo "Failed to update supervisor.";
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
