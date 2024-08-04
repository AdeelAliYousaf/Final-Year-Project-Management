<?php
session_start(); // Start the session

include "../db.php";

// Check if teacher ID is set in session (assuming you store it during login/authentication)
if (isset($_SESSION['id'])) {
    $teacher_id = $_SESSION['id'];
} else {
    // Redirect to login page or handle unauthorized access
    header("Location: ../../index.php");
    exit(); // Stop further execution
}

// Query to fetch groups assigned to the teacher
$query = "SELECT id FROM groups WHERE t_id = ?";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "i", $teacher_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Message to Students</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap");
        *{
            
            font-family: "Poppins", sans-serif;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Teacher Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../TeacherDashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Groups/main.php">Groups</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Marks/UploadMarks.php">Upload Marks</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Project Management/main.php">Old Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="SendMessage.php">Send Message</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                <a class="nav-link" href="../Logout/logout.php" style="color:red;"><i class="fas fa-power-off"></i>Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Send Message to Students</h2>
    <form action="ProcessSendMessage.php" method="post">
        <div class="mb-3">
            <label for="group_id" class="form-label">Select Group ID:</label>
            <select class="form-select" id="g_id" name="g_id" required>
                <option value="">Select Group ID</option>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <option value="<?php echo htmlspecialchars($row['id']); ?>"><?php echo htmlspecialchars($row['id']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message:</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send Message</button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

</body>
</html>
