<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT gp.g_id AS group_id, gp.p_id AS project_id, CONCAT(t.fname, ' ', t.lname) AS teacher_name, p.name AS project_name
        FROM groups_project gp
        LEFT JOIN groups g ON gp.g_id = g.id
        LEFT JOIN teacher t ON g.t_id = t.id
        LEFT JOIN project p ON gp.p_id = p.id";

$result = $conn->query($sql);

$groupProjects = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $groupProjects[] = array(
            "group_id" => $row['group_id'],
            "teacher_name" => $row['teacher_name'],
            "project_id" => $row['project_id'],
            "project_name" => $row['project_name']
        );
    }
} else {
    // Handle query error
    echo "No group projects found.";
}

// Close connection
$conn->close();

// Output JSON
echo json_encode($groupProjects);
?>
