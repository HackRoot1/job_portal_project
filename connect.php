<?php 
    // Database connection parameters
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "job_portal";

    // Attempt to establish connection
    $conn = mysqli_connect($host, $user, $pass, $db);

    // Check connection
    if (!$conn) {
        // Connection failed, display error message
        die("Connection failed: " . mysqli_connect_error());
    }
    