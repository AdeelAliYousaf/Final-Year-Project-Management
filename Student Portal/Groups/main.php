<?php
session_start(); // Start the session

include "../db.php"; // Include your database connection file

// Check if student ID is set in session (assuming you store it during login/authentication)
if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../../index.php");
    exit(); // Stop further execution
}

// Fetch group details for the student using prepared statement
$query_group = "SELECT gs.g_id, gp.p_id, p.name AS project_name, p.description, g.t_id, CONCAT(t.fname,' ',t.lname) AS teachername
                FROM groups_student gs
                INNER JOIN groups_project gp ON gs.g_id = gp.g_id
                INNER JOIN project p ON gp.p_id = p.id
                INNER JOIN groups g ON gs.g_id = g.id
                INNER JOIN teacher t ON g.t_id = t.id
                WHERE gs.s_id = ?";
$stmt = mysqli_prepare($conn, $query_group);
mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result_group = mysqli_stmt_get_result($stmt);

$sql_query = "SELECT * FROM groups_student WHERE s_id = $student_id";

$result = mysqli_query($conn, $sql_query);
$showRequest = false;

if (mysqli_num_rows($result) > 0) {
    $showRequest = false; 
} else {
    $showRequest = true;  
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");

        *{
            font-family: "Poppins", sans-serif;
        }
        .card {
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <?php include("navbar.php"); ?>
    <?php if ($showRequest): ?>
        <div class="container mt-4">
            <div class="alert alert-warning text-center" role="alert">
                <h3>To See Your Group Details, You Must be in a Group</h3>
                <h6>No Group Found!</h6>
            </div>
        </div>
    <?php endif; ?>
    <?php if($showRequest==false): ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <?php
            // Display group details
            if (mysqli_num_rows($result_group) > 0) {
                while ($group = mysqli_fetch_assoc($result_group)) {
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title"><?= htmlspecialchars($group['g_id']) ?></h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Project Name:</strong> <?= htmlspecialchars($group['project_name']) ?></p>
                            <p><strong>Description:</strong><br> <?= htmlspecialchars($group['description']) ?></p>
                            <p><strong>Group Supervisor:</strong><br> <?= htmlspecialchars($group['teachername']) ?></p>
                            <h6 class="card-subtitle mb-2 text-muted">Group Members</h6>
                            <?php
                            // Fetch group members for this group
                            include "../db.php";
                            $group_id = $group['g_id'];
                            $query_members = "SELECT s.fname, s.lname, s.email
                                              FROM groups_student gs
                                              INNER JOIN student s ON gs.s_id = s.id
                                              WHERE gs.g_id = ?";
                            $stmt_members = mysqli_prepare($conn, $query_members);
                            mysqli_stmt_bind_param($stmt_members, "s", $group_id);
                            mysqli_stmt_execute($stmt_members);
                            $members_result = mysqli_stmt_get_result($stmt_members);

                            if (mysqli_num_rows($members_result) > 0) {
                                ?>
                                <div class="mb-3">
                                    <ol>
                                        <?php
                                        while ($member = mysqli_fetch_assoc($members_result)) {
                                            ?>
                                            <li>
                                                <?= htmlspecialchars($member['fname'] . ' ' . $member['lname']) ?><br>
                                                <?= htmlspecialchars($member['email']) ?>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                    </ol>
                                </div>
                                <?php
                            } else {
                                echo "<p>No members found for this group.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "  <div class='alert alert-warning text-center' role='alert'>
                            <h3>No Project is Assigned Yet, Ask Your Advisor to accept your Project Request.</h3>
                            <h6>OR Make another Request!</h6>
                        </div>";
            }
            ?>
        </div>
    </div>
</div>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
