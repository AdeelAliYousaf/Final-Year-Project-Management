<?php
session_start(); // Ensure session is started
include("db.php");

// Check if student ID is set in session
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page if student ID is not set
    header("Location: ../../index.php");
    exit();
}

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$lang = $_POST['lang'];

// Fetch group ID for the student
$sql1 = "SELECT g_id FROM groups_student WHERE s_id = '$student_id'";
$groupresult = mysqli_query($conn, $sql1);

// Check if any group ID was found
if (mysqli_num_rows($groupresult) > 0) {
    $row = mysqli_fetch_array($groupresult);
    $group = $row['g_id'];
} else {
    // Handle case where no group ID is found
    echo "<script>
        alert('You are not assigned to any group.');
        window.location.href = '../StudentDashboard.php';
    </script>";
    mysqli_close($conn);
    exit();
}

// Insert project request into database
$sql_query = "INSERT INTO temp_projects (title, description, lang, g_id, s_id) VALUES ('$title', '$description', '$lang', '$group', '$student_id')";

// Execute query
$result = mysqli_query($conn, $sql_query);

// Check if the query was successful
if ($result) {
    // If query successful, output JavaScript for alert and redirect
    echo "<script>
        alert('Your Project Request has been Submitted. Check Your Group Details to See If Your Request Has Been Accepted.');
        window.location.href = 'request.php';
    </script>";
} else {
    // Handle query failure and output error
    echo "<script>
        alert('Failed to submit your project request. Please try again. Error: " . mysqli_error($conn) . "');
        window.location.href = 'request.php';
    </script>";
}

// Close database connection
mysqli_close($conn);
?>
