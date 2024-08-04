<?php
session_start();
include "../db.php";

if (isset($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    header("Location: ../../index.php");
    exit();
}

// Fetch the group and teacher associated with the student
$group = null;
$teacher = null;

$query = "
    SELECT g.id AS g_id, t.id AS t_id, t.fname, t.lname
    FROM teacher t
    JOIN groups g ON t.id = g.t_id
    JOIN groups_student gs ON gs.g_id = g.id
    WHERE gs.s_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $group = $row['g_id'];
    $teacher = ['id' => $row['t_id'], 'fname' => $row['fname'], 'lname' => $row['lname']];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
        * {
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body>
    
<?php include("navbar.php"); ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Send Notification</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="ProcessSendMessage.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="group" class="form-label">Group</label>
                            <input type="text" class="form-control" id="group" name="g_id" value="<?= htmlspecialchars($group) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="teacher" class="form-label">Teacher</label>
                            <input type="text" class="form-control" id="teacher" value="<?= htmlspecialchars($teacher['fname'] . ' ' . $teacher['lname']) ?>" readonly>
                            <input type="hidden" name="t_id" value="<?= $teacher['id'] ?>">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Attachment</label>
                            <input type="file" class="form-control" id="attachment" name="attachment" accept="image/*, .pdf, .docx">
                        </div>
                        <button type="submit" class="btn btn-primary">Send Notification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
