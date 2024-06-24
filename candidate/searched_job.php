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

$header_data = ['css' => 'searched_jobs', 'active' => 2];
include("./header.php");

?>

        <section class="filters-section">
            <div class="tabs">
                Job Details - <?php echo $data['job_title']; ?>
            </div>
        </section>


        <section class="display-job-data">
            <div class="table">

                <div class="row">
                    <div>Job Id :</div>
                    <div><?php echo $data['id']; ?></div>
                </div>
                <div class="row">
                    <div>Job Title :</div>
                    <div><?php echo $data['job_title']; ?></div>
                </div>
                <div class="row">
                    <div>Job Description :</div>
                    <div><?php echo $data['description']; ?></div>
                </div>
                <div class="row">
                    <div>Job Location :</div>
                    <div><?php echo $data['job_location']; ?></div>
                </div>
                <div class="row">
                    <div>Job Company :</div>
                    <div>Company Name</div>
                </div>
                <div class="row">
                    <div>Job Budget :</div>
                    <div><?php echo $data['budget_ctc']; ?></div>
                </div>
                <div class="row">
                    <div>Experience Required :</div>
                    <div> <?php echo $data['min_job_exp'] . " - " . $data['max_job_exp'] . " Years" ?></div>
                </div>

                <div class="row">

                    <div class="apply-btn">
                        <?php if (in_array($data['id'], $applied_jobs)) : ?>
                            <a href="#">Applied</a>
                        <?php else :  ?>
                            <a href="apply.php?job_id=<?php echo $data['id']; ?>">Apply</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </section>

    </main>
</body>

</html>