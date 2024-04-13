<?php 


include("../connect.php");
include("./session.php");

// Ensure 'job_id' parameter is provided
if(isset($_GET['job_id'])) {
    // Sanitize the input
    $id = mysqli_real_escape_string($conn, $_GET['job_id']);

    // Fetching logged-in user's id
    $user_id = $users_data['id'];

    // Check if the user has uploaded their resume
    if(empty($users_data['resume'])) {
        echo "Please upload your resume first.";
    } else {
        // Prepare and execute the SQL statement using prepared statements
        $sql2 = "INSERT INTO applied_jobs (candidate_id, job_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql2);
        
        // Bind parameters
        $stmt->bind_param("ii", $user_id, $id);
        
        // Execute the statement
        if($stmt->execute()) {
            header("Location: ./search_jobs.php");
            exit();
        } else {
            echo "An error occurred. Please try again.";
        }
        
        // Close the statement
        $stmt->close();
    }
} else {
    echo "Invalid request.";
}