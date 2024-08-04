<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to fetch unassigned projects
$sql = "SELECT p.id AS project_id, p.name AS project_name
        FROM project p
        WHERE p.id NOT IN (SELECT p_id FROM groups_project)";

$result = $conn->query($sql);

$unassignedProjects = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $unassignedProjects[] = array(
            "project_id" => $row['project_id'],
            "project_name" => $row['project_name']
        );
    }
} else {
    // Handle query error
    echo json_encode(array("error" => "No unassigned projects found."));
    exit;
}

// Close connection
$conn->close();

// Output JSON
header('Content-Type: application/json');
echo json_encode($unassignedProjects);
?>
