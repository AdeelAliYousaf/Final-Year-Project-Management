
<?php
include 'db.php';

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT DISTINCT g.id AS group_id
        FROM groups g";

$result = $conn->query($sql);

$groups = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $group_id = $row['group_id'];

        // Fetch teacher name for the group
        $teacher_sql = "SELECT CONCAT(t.fname , ' ' , t.lname) AS teacher_name
                        FROM groups g
                        LEFT JOIN teacher t ON g.t_id = t.id
                        WHERE g.id = ?";

        $stmt_teacher = $conn->prepare($teacher_sql);
        $stmt_teacher->bind_param("s", $group_id);
        $stmt_teacher->execute();
        $teacher_result = $stmt_teacher->get_result();
        $teacher_row = $teacher_result->fetch_assoc();

        // Fetch student names for the group
        $student_sql = "SELECT s.fname AS student_name
                        FROM groups_student gs
                        LEFT JOIN student s ON gs.s_id = s.id
                        WHERE gs.g_id = ?";

        $stmt_student = $conn->prepare($student_sql);
        $stmt_student->bind_param("s", $group_id);
        $stmt_student->execute();
        $student_result = $stmt_student->get_result();

        $student_names = array();
        while ($student_row = $student_result->fetch_assoc()) {
            $student_names[] = $student_row['student_name'];
        }

        // Combine group ID, teacher name, and student names into a single array
        $group = array(
            "group_id" => $group_id,
            "teacher_name" => $teacher_row['teacher_name'],
            "student_names" => $student_names
        );

        $groups[] = $group;
    }
} else {
    // Handle query error
    echo "No groups found.";
}

// Close statements
$stmt_teacher->close();
$stmt_student->close();

// Close connection
$conn->close();

// Output JSON
echo json_encode($groups);
?>
