<?php 
    session_start();
    // Redirect to login page if the user is not logged in
    if(!isset($_SESSION['username'])){
        header("Location: ./login.php");
        exit();
    }

    // Logout functionality
    if(isset($_POST['logout'])) {
        session_destroy();
        header("Location: ./login.php");
        exit();
    }

    // Fetch user data
    include("../connect.php");
    $username = $_SESSION['username'];
    $users_query = "SELECT * FROM users_data WHERE username = ?";
    $stmt = $conn->prepare($users_query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $users_result = $stmt->get_result();
    $users_data = $users_result->fetch_assoc();
    $stmt->close();

    // Check user role
    if($users_data['role'] != 'employer'){
        session_destroy();
        header("Location: ./login.php");
        exit();
    }
?>
