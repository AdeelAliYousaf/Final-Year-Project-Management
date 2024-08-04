<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['group_ids']) && isset($_POST['project_ids'])) {
    $groupIds = $_POST['group_ids'];
    $projectIds = $_POST['project_ids'];

    // Insert assignments into groups_project table
    $stmt = $conn->prepare("INSERT INTO groups_project (g_id, p_id) VALUES (?, ?)");
    $stmt->bind_param("ss", $groupId, $projectId);

    foreach ($groupIds as $groupId) {
        foreach ($projectIds as $projectId) {
            $stmt->execute();
        }
    }

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
