<?php 

include("../connect.php");
include("./session.php");


if(isset($_GET['job_id'])) {

    $id = mysqli_real_escape_string($conn, $_GET['job_id']);
    $user_id = $users_data['id'];

    $stmt = $conn->prepare("UPDATE applied_jobs SET status = 1 WHERE candidate_id = ? AND job_id = ?");
    $stmt->bind_param("ii", $user_id, $id);

    if($stmt->execute()) {
        header("Location: ./my_applied_jobs.php");
        exit();
    }else {
        echo "An error occurred. Please try again.";
    }

    $stmt->close();

}else {
    echo "Invalid Request";
}

