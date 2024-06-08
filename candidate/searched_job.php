<?php 
    include("../connect.php");
    include("./session.php");

    // Retrieve job ID from the URL parameter
    $job_id = mysqli_real_escape_string($conn, $_GET['job_id']);

    // Fetch employer's posted job data
    $sql = "SELECT * FROM posted_jobs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    // Get user's applied job data
    $applied_jobs_query = "SELECT DISTINCT job_id FROM applied_jobs WHERE candidate_id = ?";
    $stmt2 = $conn->prepare($applied_jobs_query);
    $stmt2->bind_param("i", $users_data['id']);
    $stmt2->execute();
    $applied_jobs_result = $stmt2->get_result();

    // Store applied job IDs in an array
    $applied_jobs = [];
    while ($row = $applied_jobs_result->fetch_assoc()) {
        $applied_jobs[] = $row['job_id'];
    }
    $stmt2->close();

    // Include the main header
    $header_data = ['id' => 2, 'title' => 'Searched Jobs', 'css' => 'searched_jobs'];
    include("./main_header.php");
?>






    <!-- ======================== Main section ====================== -->

    <main>

        <section class = "main-title">
            <!-- Dynamically changed -->
            Job Details - <?php echo $data['job_title']; ?>
        </section>



        <section class = "job-details">
            
            <div>

                <div>
                    <div>
                        <span>Job Id :</span> <span> <?php echo $data['id']; ?></span>
                    </div>
                </div>

                <div>
                    <div>
                        <span>Job Title :</span> <span> <?php echo $data['job_title']; ?></span>
                    </div>
                </div>

                <div>
                    <div>
                        <span>Job Description :</span> <span> <?php echo $data['description']; ?></span>
                    </div>
                </div>

                <div>
                    <div>
                        <span>Job Location :</span> <span> <?php echo $data['job_location']; ?></span>
                    </div>
                </div>

                <div>
                    <div>
                        <span>Job Company :</span> <span> Company Name</span>
                    </div>
                </div>

                <div>
                    <div>
                        <span>Job Budget :</span> <span> <?php echo $data['budget_ctc']; ?></span>
                    </div>
                </div>
                <div>
                    <div>
                        <span>Experience Required :</span> <span> <?php echo $data['min_job_exp'] . " - " . $data['max_job_exp'] . " Years" ?></span>
                    </div>
                </div>


                <div class = "apply-btn">
                    <?php if(in_array($data['id'], $applied_jobs)): ?>
                        <a href="#">Applied</a>
                        <?php else:  ?>
                            <a href="apply.php?job_id=<?php echo $data['id']; ?>">Apply</a>
                    <?php endif; ?>
                </div>

            </div>

        </section>

    </main>
    <!-- ========================= End Main ========================= -->



    
<?php require("../footer.php"); ?>