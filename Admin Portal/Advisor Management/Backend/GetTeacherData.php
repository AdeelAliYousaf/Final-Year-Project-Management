<?php
include "db.php";

// Check if student ID is provided
if(isset($_GET['id'])) {
    $id = $_GET['id'];

    // Function to get student details by ID
    function getStudent($conn, $id) {
        $query = "SELECT * FROM teacher where id = '$id'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        } else {
            return null;
        }
    }

    // Get student data
    $studentData = getStudent($conn, $id);

    // Check if student data is retrieved
    if ($studentData) {
        // Return student data in JSON format
        header('Content-Type: application/json');
        echo json_encode($studentData);
        exit;
    } else {
        // Return empty response if student data not found
        echo json_encode(array('error' => 'Student data not found'));
    }
} else {
    // Return error response if student ID is not provided
    echo json_encode(array('error' => 'Student ID is not provided'));
}
?>
