<?php 
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "FYP";

    $conn = mysqli_connect($host,$username,$password,$database);

    if (!$conn)
    {
        echo "<script>alert('Database is not connected Properly!')</script>";
    }
?>