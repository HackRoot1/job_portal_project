<?php 
    // Check if job_id is set in the URL parameters
    if(isset($_GET['job_id'])) {
        // Assign job_id from the URL parameter
        $job_id = $_GET['job_id'];
    } else {
        // Display a specific error message if job_id is not provided
        die("Job ID is missing.");
    }

    // Include necessary files
    include("../connect.php");
    include("./session.php");

    // Sanitize job_id to prevent SQL injection
    $job_id = mysqli_real_escape_string($conn, $job_id);

    // Construct the SQL query to delete the job posting
    $sql = "DELETE FROM posted_jobs WHERE id = '{$job_id}'";

    // Execute the query
    if(mysqli_query($conn, $sql)){
        // Redirect to my_jobs.php after successful deletion
        header("Location: ./my_jobs.php");
        exit();
    } else {
        // Display an error message if the query fails
        die("Failed to delete the job posting.");
    }
?>
