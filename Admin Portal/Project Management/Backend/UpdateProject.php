<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from the POST request
    $projectId = $_POST['id'];
    $projectName = $_POST['name'];
    $projectDescription = $_POST['description'];
    $projectLanguages = $_POST['lang'];

    // Update the project information in the database
    $sql = "UPDATE project SET name=?, description=?, lang=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $projectName, $projectDescription, $projectLanguages, $projectId);

    if ($stmt->execute()) {
        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            // Return success response
            echo json_encode(['success' => true]);
        } else {
            // Return error response if no rows were affected
            echo json_encode(['success' => false, 'message' => 'No rows were affected by the update.']);
        }
    } else {
        // Return error response if the query fails
        echo json_encode(['success' => false, 'message' => 'Failed to execute the update query: ' . $conn->error]);
    }

    // Close statement
    $stmt->close();
    // Close connection
    $conn->close();
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
