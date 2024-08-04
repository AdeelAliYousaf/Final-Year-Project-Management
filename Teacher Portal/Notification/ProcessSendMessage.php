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

// Validate input
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['g_id']) && isset($_POST['message'])) {
        $group_id = $_POST['g_id'];
        $message = $_POST['message'];

        // Fetch all student IDs (s_id) in the selected group (g_id) for the current teacher (t_id)
        $query = "SELECT gs.s_id FROM groups_student gs 
                  INNER JOIN groups g ON gs.g_id = g.id
                  WHERE gs.g_id = ? AND g.t_id = ?";
        
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_bind_param($stmt, "si", $group_id, $teacher_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            // Check if there are students in the group
            if (mysqli_num_rows($result) > 0) {
                // Insert message for each student in student_notification table
                $insert_query = "INSERT INTO student_notification (s_id, g_id, message) VALUES (?, ?, ?)";
                $stmt_insert = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt_insert, $insert_query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $student_id = $row['s_id'];
                        mysqli_stmt_bind_param($stmt_insert, "iss", $student_id, $group_id, $message);
                        mysqli_stmt_execute($stmt_insert);
                    }
                    mysqli_stmt_close($stmt_insert);
                    mysqli_stmt_close($stmt);
                    echo "<script>alert('Message has been sent to all the group members')</script>";
                    echo "<script>window.location.href='SendMessage.php'</script>";
                    

                } else {
                    echo "Error preparing statement for inserting message: " . mysqli_error($conn);
                    exit();
                }
            } else {
                echo "No students found in the selected group.";
                exit();
            }
        } else {
            echo "Error preparing statement for fetching student IDs: " . mysqli_error($conn);
            exit();
        }
    } else {
        echo "Group ID or message not provided.";
        exit();
    }
} else {
    echo "Method not allowed.";
    exit();
}
?>
