<?php 
    session_start();
    // Redirect to login page if the user is not logged in
    if(!isset($_SESSION['username'])){
        header("Location: ../login.php?role=employee");
        exit();
    }

    // Logout functionality
    if(isset($_POST['logout'])) {
        session_destroy();
        header("Location: ../login.php?role=employee");
        exit();
    }

    // Fetch user data
    include("../connect.php");
    $username = $_SESSION['username'];
    $users_query = "SELECT * FROM users_data WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($users_query);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $users_result = $stmt->get_result();
    $users_data = $users_result->fetch_assoc();
    $stmt->close();

    // Check user role
    if($users_data['role'] != 'employee'){
        session_destroy();
        header("Location: ../login.php?role=employee");
        exit();
    }
