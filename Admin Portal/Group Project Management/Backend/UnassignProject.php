<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $groupId = $_POST['group_id'];

    // Delete the assignment from groups_project
    $sql = "DELETE FROM groups_project WHERE g_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $groupId);
    $stmt->execute();

    // Close connection
    $stmt->close();
    $conn->close();

    // Redirect to the assign new project page
    header('Location: ../AssignNewProject.php?group_id=' . $groupId);
    exit;
} else {
    echo "Invalid request method.";
}
?>
