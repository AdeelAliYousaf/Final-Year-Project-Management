<?php
// Check if the group_id parameter is set in the URL
if(isset($_GET['group_id'])) {
    // Retrieve the group ID from the URL
    $group_id = $_GET['group_id'];

    // Fetch group details from the database based on the group ID
    include 'db.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and execute a query to fetch group details
    $stmt = $conn->prepare(" SELECT g.id AS group_id,
           s.id AS student_id, 
           s.fname AS student_fname, 
           s.lname AS student_lname,
           sc.class AS student_class, 
           sc.batch AS student_batch, 
           sc.rollno AS student_rollno,
           p.name AS title,
           p.lang AS lang,
           p.description AS description
            FROM groups g
            JOIN groups_student gs ON g.id = gs.g_id
            JOIN groups_project gp ON gp.g_id = g.id
            JOIN project p ON p.id = gp.p_id
            JOIN student s ON gs.s_id = s.id
            JOIN student_class sc ON s.id = sc.id
            WHERE g.id = ?
        ");
    $stmt->bind_param("s", $group_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a group with the given ID exists
    if($result->num_rows > 0) {
        // Fetch all group details into an array
        $group_details = $result->fetch_all(MYSQLI_ASSOC);

        // Close statement
        $stmt->close();

        // Close connection
        $conn->close();

        // Display group details
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Group Details</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
        </head>
        <body>
            <?php include "../SideBar.php" ?>
            <div class="container mt-5">
                <h1>Group Details</h1>
                <hr>
                <!-- Back button -->
                <a href="#" onclick="history.back()" class="btn btn-primary mb-3"><i class="fas fa-arrow-left"></i> Back</a>
                <!-- Group details -->
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Group ID:</strong> <?php echo $group_details[0]['group_id']; ?></p>

                        <p><strong>Project Title: </strong><?php echo $group_details[0]['title'] ?></p>

                        <p><strong>Project Description: </strong><?php echo $group_details[0]['description'] ?></p>
                        
                        <p><strong>Project Languages: </strong><?php echo $group_details[0]['lang'] ?></p>

                        <p><strong>Group Members:</strong></p>
                        
                        <ul>
                            <?php foreach($group_details as $row) { ?>
                                <li><?php echo $row['student_rollno'] . " " . $row['student_fname'] . " " . $row['student_lname'] . " - " . $row['student_class']. " - " . $row['student_batch']; ?></li>
                            <?php } ?>
                        </ul>
                        
                    </div>
                </div>
            </div>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        // If no group with the given ID is found, display an error message
        echo "Group not found.";
    }
} else {
    // If group_id parameter is not set in the URL, display an error message
    echo "Group ID parameter not provided.";
}
?>
