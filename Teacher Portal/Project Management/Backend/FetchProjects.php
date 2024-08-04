<?php
// Include database connection
include 'db.php';

// Check if session is started
session_start();

// Check if teacher_id is set in session
if (!isset($_SESSION['id'])) {
    die("Teacher not logged in.");
}

// Fetch teacher_id from session
$teacher_id = $_SESSION['id'];

// SQL query to select projects supervised by the logged-in teacher
$sql = "SELECT p.id AS project_id, p.name AS project_name, p.description AS project_description, p.lang AS project_language
        FROM project p
        INNER JOIN groups_project gp ON p.id = gp.p_id
        INNER JOIN groups g ON gp.g_id = g.id
        WHERE g.t_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();

$projects = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $projects[] = array(
            "project_id" => $row['project_id'],
            "project_name" => $row['project_name'],
            "project_description" => $row['project_description'],
            "project_language" => $row['project_language']
        );
    }
} else {
    // Handle no projects found
    echo "No projects supervised by this teacher.";
}

// Close statement and connection
$stmt->close();
$conn->close();

// Output JSON
header('Content-Type: application/json');
echo json_encode($projects);
?>
