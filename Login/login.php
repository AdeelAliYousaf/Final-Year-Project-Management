<?php
include '../Database Connection/db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if login is for admin
    $query = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        $_SESSION['username'] = $admin['username'];
        $_SESSION['id'] = $admin['id']; // Store admin ID in session
        header("location: ../Admin Portal/AdminDashboard.php");
        exit();
    }

    // Check if login is for teacher
    $query = "SELECT * FROM teacher_cred WHERE email='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $teacher = $result->fetch_assoc();
        // Fetch teacher ID from the main 'teacher' table using 't_id'
        $teacher_id = $teacher['t_id'];
        $query_teacher = "SELECT * FROM teacher WHERE id='$teacher_id'";
        $result_teacher = $conn->query($query_teacher);
        
        if ($result_teacher->num_rows > 0) {
            $teacher_info = $result_teacher->fetch_assoc();
            $_SESSION['username'] = $teacher_info['email'];
            $_SESSION['id'] = $teacher_info['id']; // Store teacher ID in session
            header("location: ../Teacher Portal/TeacherDashboard.php");
            exit();
        }
    }

    // Check if login is for student
    $query = "SELECT * FROM student_cred WHERE username='$username' AND password='$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        // Fetch student ID from the main 'student' table using 's_id'
        $student_id = $student['s_id'];
        $query_student = "SELECT * FROM student WHERE id='$student_id'";
        $result_student = $conn->query($query_student);

        if ($result_student->num_rows > 0) {
            $student_info = $result_student->fetch_assoc();
            $_SESSION['username'] = $student_info['username'];
            $_SESSION['id'] = $student_info['id']; // Store student ID in session
            header("location: ../Student Portal/StudentDashboard.php");
            exit();
        }
    }

    // If no valid user found, redirect back to login with an error message
    echo "<script>alert('Invalid Credentials!')</script>";
    echo "<script>window.location.href='../index.php'</script>";
}
?>
