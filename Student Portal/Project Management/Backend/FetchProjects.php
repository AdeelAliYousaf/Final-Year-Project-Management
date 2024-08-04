<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id AS project_id, name AS project_name, description AS project_description, lang AS project_language FROM project";

$result = $conn->query($sql);

$projects = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $projects[] = array(
            "project_id" => $row['project_id'],
            "project_name" => $row['project_name'],
            "project_description" => $row['project_description'],
            "project_language" => $row['project_language']
        );
    }
} else {
    // Handle query error
    echo "No projects found.";
}

// Close connection
$conn->close();

// Output JSON
echo json_encode($projects);
?>
