<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL to fetch unassigned groups
$sql = "SELECT g.id AS group_id, CONCAT(t.fname, ' ', t.lname) AS teacher_name
        FROM groups g
        LEFT JOIN teacher t ON g.t_id = t.id
        WHERE g.id NOT IN (SELECT g_id FROM groups_project)";

$result = $conn->query($sql);

$unassignedGroups = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $unassignedGroups[] = array(
            "group_id" => $row['group_id'],
            "teacher_name" => $row['teacher_name']
        );
    }
} else {
    // Handle query error
    echo json_encode(array("error" => "No unassigned groups found."));
    exit;
}

// Close connection
$conn->close();

// Output JSON
header('Content-Type: application/json');
echo json_encode($unassignedGroups);
?>
