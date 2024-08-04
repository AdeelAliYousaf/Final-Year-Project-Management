<?php
include "db.php"; // Include your database connection file

// Check if group_id is set in $_GET
if (!isset($_GET['group_id'])) {
    die("Invalid group ID.");
}

$group_id = intval($_GET['group_id']);

// Fetch group details
$query = "SELECT * FROM groups WHERE id = $group_id";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Group not found.");
}

$group = mysqli_fetch_assoc($result);

// Fetch current group members
$query_members = "SELECT student.id, student.fname, student.lname FROM student 
                  INNER JOIN groups_student ON student.id = groups_student.s_id 
                  WHERE groups_student.g_id = $group_id";
$result_members = mysqli_query($conn, $query_members);

// Fetch students who are not in any group
$query_all_students = "SELECT student.id, student.fname, student.lname FROM student 
                       LEFT JOIN groups_student ON student.id = groups_student.s_id 
                       WHERE groups_student.s_id IS NULL";
$result_all_students = mysqli_query($conn, $query_all_students);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Group</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
</head>
<body>
    <?php include "../SideBar.php" ?>

    <div class="container">
        <h1>Edit Group: <?= htmlspecialchars($group['id']) ?></h1>
        <div class="mb-3">
            <button class="btn btn-primary" id="changeMembersBtn">Change Group Members</button>
            <button class="btn btn-primary" id="changeSupervisorBtn">Change Group Supervisor</button>
        </div>
        
        <!-- Change Group Members Section -->
        <div id="changeMembersSection" style="display: none;">
            <h2>Change Group Members</h2>
            <div class="row">
                <div class="col-md-6">
                    <h3>Current Members</h3>
                    <table id="currentMembersTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_members)) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['fname'] . " " . $row['lname']) ?></td>
                                    <td><button class="btn btn-sm btn-danger remove-member-btn" data-id="<?= $row['id'] ?>">Remove</button></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h3>Available Students</h3>
                    <table id="availableStudentsTable" class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result_all_students)) : ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['id']) ?></td>
                                    <td><?= htmlspecialchars($row['fname'] . " " . $row['lname']) ?></td>
                                    <td><button class="btn btn-sm btn-success add-member-btn" data-id="<?= $row['id'] ?>">Add</button></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Change Group Supervisor Section -->
        <div id="changeSupervisorSection" style="display: none;">
            <h2>Change Group Supervisor</h2>
            <!-- Form to change supervisor -->
            <form action="UpdateSupervisor.php" method="POST">
                <input type="hidden" name="group_id" value="<?= $group_id ?>">
                <div class="mb-3">
                    <label for="supervisor" class="form-label">New Supervisor</label>
                    <select class="form-select" id="supervisor" name="t_id">
                        <?php
                        $query_supervisors = "SELECT * FROM teacher";
                        $result_supervisors = mysqli_query($conn, $query_supervisors);
                        while ($row = mysqli_fetch_assoc($result_supervisors)) {
                            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['fname'] . " " . $row['lname']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Supervisor</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#changeMembersBtn').click(function() {
                $('#changeSupervisorSection').hide();
                $('#changeMembersSection').toggle();
            });

            $('#changeSupervisorBtn').click(function() {
                $('#changeMembersSection').hide();
                $('#changeSupervisorSection').toggle();
            });

            $('.remove-member-btn').click(function() {
                var studentId = $(this).data('id');
                // Make an AJAX request to remove the student from the group
                $.ajax({
                    url: 'Remove.php',
                    type: 'POST',
                    data: {
                        group_id: <?= $group_id ?>,
                        student_id: studentId
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });

            $('.add-member-btn').click(function() {
                var studentId = $(this).data('id');
                // Make an AJAX request to add the student to the group
                $.ajax({
                    url: 'Add.php',
                    type: 'POST',
                    data: {
                        group_id: <?= $group_id ?>,
                        student_id: studentId
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
</body>
</html>
