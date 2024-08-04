<?php
session_start();
include "db.php";

if (isset($_SESSION['id'])) {
    $teacherid = $_SESSION['id'];
} else {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    // Update status in temp_projects table
    if ($action == 'accept') {
        $sql_query = "UPDATE temp_projects SET status = 'accepted' WHERE id = '$request_id' AND t_id = '$teacherid'";
    } elseif ($action == 'reject') {
        $sql_query = "UPDATE temp_projects SET status = 'rejected' WHERE id = '$request_id' AND t_id = '$teacherid'";
    }
    
    $result = mysqli_query($conn, $sql_query);

    if ($result) {
        if ($action == 'accept') {
            // Retrieve project details from temp_projects table
            $project_query = "SELECT title, description, lang, g_id FROM temp_projects WHERE id = '$request_id'";
            $project_result = mysqli_query($conn, $project_query);

            if (mysqli_num_rows($project_result) > 0) {
                $project = mysqli_fetch_assoc($project_result);
                $title = mysqli_real_escape_string($conn, $project['title']);
                $description = mysqli_real_escape_string($conn, $project['description']);
                $lang = mysqli_real_escape_string($conn, $project['lang']);
                $g_id = $project['g_id'];
                
                // Insert into projects table
                $insert_query = "INSERT INTO project (name, description, lang) VALUES ('$title', '$description', '$lang')";
                $insert_result = mysqli_query($conn, $insert_query);
                
                if ($insert_result) {
                    // Get the last inserted p_id
                    $p_id = mysqli_insert_id($conn);

                    // Insert into groups_project table
                    $group_project_query = "INSERT INTO groups_project (g_id, p_id) VALUES ('$g_id', '$p_id')";
                    $group_project_result = mysqli_query($conn, $group_project_query);

                    if ($group_project_result) {
                        echo "<script>
                            alert('Project accepted and added successfully.');
                            window.location.href = 'TeacherDashboard.php'; // Update with the correct redirect page
                        </script>";
                    } else {
                        echo "<script>
                            alert('Failed to add project to group. Please try again.');
                            window.location.href = 'TeacherDashboard.php'; // Update with the correct redirect page
                        </script>";
                    }
                } else {
                    echo "<script>
                        alert('Failed to add project. Please try again.');
                        window.location.href = 'TeacherDashboard.php'; // Update with the correct redirect page
                    </script>";
                }
            }
        } else {
            echo "<script>
                alert('Request has been rejected.');
                window.location.href = 'TeacherDashboard.php'; // Update with the correct redirect page
            </script>";
        }
    } else {
        echo "<script>
            alert('Failed to process the request. Please try again.');
            window.location.href = 'TeacherDashboard.php'; // Update with the correct redirect page
        </script>";
    }
    
    mysqli_close($conn);
}
?>
