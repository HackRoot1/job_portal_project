<?php 
    include("../connect.php");
    include("./session.php");

    // Initialize job_id variable
    $job_id = null;

    // Check if job_id is set in the URL parameters
    if(isset($_GET['job_id'])){
        // Sanitize job_id to prevent SQL injection
        $job_id = mysqli_real_escape_string($conn, $_GET['job_id']);
    } else {
        // Display a specific error message if job_id is not provided
        die("Job ID is missing.");
    }

    // Fetch applied employees for the specified job_id
    $sql = "SELECT * FROM applied_jobs WHERE job_id = '{$job_id}'";
    $result = mysqli_query($conn, $sql) or die("Query Failed");

    // Include header file
    $header_data = ['id' => 3, 'title' => 'Applied Employee Page'];
    require('./header.php');
?>

    <!-- ======================== Main section ====================== -->

    <main>
        <section class = "main-title">
            <!-- Dynamically changed -->
            All Applicants for PHP Developer
        </section>
        
        <section class = "table-section">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Current CTC (Lakhs)</th>
                        <th>Current Location</th>
                        <th>Action</th>
                    </tr>
                </thead>


                <!-- =================== dynamic database content 👇 -->
                <tbody>

                <?php 
                    if(mysqli_num_rows($result) > 0) {
                        while($data = mysqli_fetch_assoc($result)) {

                            // fetch candidate data 
                            $candidate_query = "SELECT * FROM users_data WHERE id = '{$data['candidate_id']}'";
                            $candidate_result = mysqli_query($conn, $candidate_query);

                            $candidate_info = mysqli_fetch_assoc($candidate_result);
                            
                            // fetch job data
                            $job_query = "SELECT * FROM posted_jobs WHERE id = '{$data['job_id']}'";
                            $job_result = mysqli_query($conn, $job_query);
                            $job_info = mysqli_fetch_assoc($job_result);

                ?>
                    <tr>
                        <td><?php echo $candidate_info['firstName'] ?></td>
                        <td><?php echo $job_info['job_title'] ?></td>
                        <td><?php echo $candidate_info['current_ctc']; ?></td>
                        <td><?php echo $candidate_info['current_location']; ?></td>
                        <td><a href="<?php echo $candidate_info['resume'] != "" ? "../assets/files/" .$candidate_info['resume'] : "#" ?>">View Resume</a></td>
                    </tr>
                    
                <?php 
                        }
                    }else {
                ?>
                    <tr>
                        <td colspan = "5">No Applications Found</td>
                    </tr>
                <?php
                    }
                ?>

                </tbody>
            </table>

        </section>
    </main>
    <!-- ========================= End Main ========================= -->




    <?php require("../footer.php"); ?>