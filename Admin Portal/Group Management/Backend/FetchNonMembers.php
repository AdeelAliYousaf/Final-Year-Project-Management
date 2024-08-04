<?php
// Include your database connection file
include "db.php";

// Query to fetch non-members from the database
$query = "SELECT student.id, CONCAT(student.fname, ' ', student.lname) AS name, student_class.class, student_class.batch,student_class.rollno
          FROM student 
          LEFT JOIN student_class ON student.id = student_class.s_id
          WHERE student.id NOT IN (SELECT s_id FROM groups_student)";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch data and store in an array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Free result set
mysqli_free_result($result);

// Close connection
mysqli_close($conn);

// Return data as JSON
echo json_encode($data);
?>
