<?php 
    // Start session and redirect if user is not logged in
    session_start();
    if(!isset($_SESSION['username'])){
        redirectToLogin();
    }

    // Handle logout request
    if(isset($_POST['logout'])) {
        session_destroy();
        redirectToLogin();
    }

    // Fetch user data from the database
    $users_query = "SELECT * FROM users_data WHERE username = ?";
    $stmt = $conn->prepare($users_query);
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $users_result = $stmt->get_result();
    $users_data = $users_result->fetch_assoc();
    $stmt->close();

    // Check user role and redirect if not candidate
    if($users_data['role'] != 'candidate'){
        session_destroy();
        redirectToLogin();
    }

    // Function to redirect to login page
    function redirectToLogin() {
        header("Location: ./login.php");
        exit();
    }
