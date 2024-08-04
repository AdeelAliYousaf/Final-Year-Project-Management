<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include "SideBar.php"; ?>
  <main class="container-fluid">
    <h1>Main Content</h1>
    <p>This is where your main content goes.</p>
    <div class="row">
      <div class="col-md-6">
        <canvas id="studentChart" width="400" height="200"></canvas>
      </div>
      <div class="col-md-6">
        <canvas id="teacherChart" width="400" height="200"></canvas>
      </div>
    </div>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
  <script>
    // PHP code to retrieve data from database
    <?php
    include '../Database Connection/db.php';

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to count the number of teachers
    $teacherCountQuery = "SELECT COUNT(*) AS teacher_count FROM teacher";
    $teacherResult = $conn->query($teacherCountQuery);
    $teacherCount = $teacherResult->fetch_assoc()['teacher_count'];

    // Query to count the number of students
    $studentCountQuery = "SELECT COUNT(*) AS student_count FROM student";
    $studentResult = $conn->query($studentCountQuery);
    $studentCount = $studentResult->fetch_assoc()['student_count'];

    // Close connection
    $conn->close();
    ?>

    // JavaScript code to create charts
    var teacherChartCanvas = document.getElementById('teacherChart').getContext('2d');
    var studentChartCanvas = document.getElementById('studentChart').getContext('2d');

    var teacherChart = new Chart(teacherChartCanvas, {
      type: 'bar',
      data: {
        labels: ['Teachers'],
        datasets: [{
          label: 'Number of Teachers',
          data: [<?php echo $teacherCount; ?>],
          backgroundColor: 'rgba(255, 99, 132, 0.2)',
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    var studentChart = new Chart(studentChartCanvas, {
      type: 'bar',
      data: {
        labels: ['Students'],
        datasets: [{
          label: 'Number of Students',
          data: [<?php echo $studentCount; ?>],
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  </script>

</body>
</html>
