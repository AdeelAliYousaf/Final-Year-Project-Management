<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['group_id']) && isset($_POST['project_id'])) {
    $groupId = $_POST['group_id'];
    $projectId = $_POST['project_id'];

    // Insert assignment into groups_project table
    $sql = "INSERT INTO groups_project (g_id, p_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $groupId, $projectId);
    $stmt->execute();

    // Close statement
    $stmt->close();

    // Close connection
    $conn->close();

    // Redirect or output success message
    header('Location: ../main.php');
    exit;
} else {
    echo "Invalid request method or missing parameters.";
}
?>
