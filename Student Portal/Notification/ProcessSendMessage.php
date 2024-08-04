<?php
include '../db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $g_id = mysqli_real_escape_string($conn, $_POST['g_id']);
    $t_id = mysqli_real_escape_string($conn, $_POST['t_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // File handling
    $file_uploaded = false;
    $upload_dir = 'attachments/'; // Directory to store attachments

    if ($_FILES['attachment']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['attachment']['tmp_name'])) {
        $file_name = basename($_FILES['attachment']['name']);
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        
        // Constructing the new file name with t_id prefix
        $new_file_name = $t_id . '_' . uniqid() . '.' . $file_extension; // Using uniqid() for uniqueness
        
        $file_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $file_path)) {
            $file_uploaded = true;
        } else {
            echo "Failed to move uploaded file.";
        }
    }

    // SQL query to insert message and attachment details into database
    $sql1 = "SET FOREIGN_KEY_CHECKS = 0";
    $conn->query($sql1);
    $sql = "INSERT INTO teacher_notification (t_id, message, g_id, attachment) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isss", $t_id, $message,$g_id, $file_path);

    // Execute query
    if (mysqli_stmt_execute($stmt)) {
        echo "Message sent successfully.";
        $sql2 = "SET FOREIGN_KEY_CHECKS = 1";
        $conn->query($sql2);
    } else {
        echo "Error sending message: " . mysqli_error($conn);
    }

    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
