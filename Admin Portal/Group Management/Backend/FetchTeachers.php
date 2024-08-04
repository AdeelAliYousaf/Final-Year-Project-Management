<?php
// Include your database connection file
include "db.php";

// Query to fetch non-members from the database
$query = "SELECT id, CONCAT(fname, ' ',lname) AS name, dept, desg FROM teacher";

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
