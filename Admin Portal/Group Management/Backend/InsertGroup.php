<?php
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required fields are set in the $_POST array
    if (isset($_POST['groupID']) && isset($_POST['teacherID']) && isset($_POST['studentIDs'])) {
        // Retrieve data from the POST request
        $groupID = $_POST['groupID'];
        $teacherID = $_POST['teacherID'];
        $studentIDs = $_POST['studentIDs'];

        // Perform database operations to insert the group, teacher, and assign students
        include "db.php"; // Include database connection

        // Insert group
        $groupInsertQuery = "INSERT INTO groups (id, t_id) VALUES (?, ?)";
        $groupInsertStmt = mysqli_prepare($conn, $groupInsertQuery);
        mysqli_stmt_bind_param($groupInsertStmt, "ss", $groupID, $teacherID);
        $groupInsertResult = mysqli_stmt_execute($groupInsertStmt);

        if ($groupInsertResult) {
            // Insert students
            $studentInsertQuery = "INSERT INTO groups_student (s_id, g_id) VALUES (?, ?)";
            $studentInsertStmt = mysqli_prepare($conn, $studentInsertQuery);
            foreach ($studentIDs as $studentID) {
                mysqli_stmt_bind_param($studentInsertStmt, "ss", $studentID, $groupID);
                mysqli_stmt_execute($studentInsertStmt);
            }

            // Send success response
            $response = array("success" => true, "message" => "Group inserted successfully");
            echo json_encode($response);
        } else {
            // Send error response
            $response = array("success" => false, "message" => "Failed to insert group: " . mysqli_error($conn));
            echo json_encode($response);
        }

        // Close prepared statements
        mysqli_stmt_close($groupInsertStmt);
        mysqli_stmt_close($studentInsertStmt);
    } else {
        // Send error response if required fields are not set
        $response = array("success" => false, "message" => "Required fields are not set");
        echo json_encode($response);
    }
} else {
    // If the request method is not POST, return an error response
    $response = array("success" => false, "message" => "Invalid request method");
    echo json_encode($response);
}
?>
