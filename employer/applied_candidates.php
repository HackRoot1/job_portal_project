<?php
include("../connect.php");
include("./session.php");

// Initialize job_id variable
$job_id = null;

// Check if job_id is set in the URL parameters
if (isset($_GET['job_id'])) {
    // Sanitize job_id to prevent SQL injection
    $job_id = mysqli_real_escape_string($conn, $_GET['job_id']);
} else {
    // Display a specific error message if job_id is not provided
    die("Job ID is missing.");
}

// Fetch applied employees for the specified job_id
$sql = "SELECT * FROM applied_jobs WHERE job_id = '{$job_id}'";
$result = mysqli_query($conn, $sql) or die("Query Failed");

$header_data = ['css' => 'applied_candidates', 'active' => 3];
include("./header.php");

?>



        <section class="filters-section">
            <div class="tabs">
                <div class="tab-link active">All</div>
                <div class="tab-link">Pending</div>
                <div class="tab-link">Completed</div>
            </div>
            <div class="sorts">
                <div class="sort">31-01-2000</div>
                <div>To</div>
                <div class="sort">31-01-2000</div>
            </div>
        </section>


        <section class="result-table-data">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Job Title</th>
                        <th>Current CTC (Lakhs)</th>
                        <th>Current Location</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>


                <!-- =================== dynamic database content 👇 -->
                <tbody>

                    <?php
                    if (mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_assoc($result)) {

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
                                <td><?php echo $candidate_info['id'] ?></td>
                                <td><?php echo $candidate_info['firstName'] ?></td>
                                <td><?php echo $job_info['job_title'] ?></td>
                                <td><?php echo $candidate_info['current_ctc']; ?></td>
                                <td><?php echo $candidate_info['current_location']; ?></td>
                                <td>status</td>
                                <td><a class="btns" style="--c: green" href="<?php echo $candidate_info['resume'] != "" ? "../assets/files/" . $candidate_info['resume'] : "#" ?>">View Resume</a></td>
                            </tr>

                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td style="text-align: center;" colspan="5">No Applications Found</td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </section>


        <section class="pagination">
            <div class="display-details">Showing 5-10 of 23</div>
            <div class="display-pages">
                <div class="pre">&lt;</div>
                <div class="page">1</div>
                <div class="page">2</div>
                <div class="page">3</div>
                <div class="next">&gt;</div>
            </div>
        </section>
    </main>
</body>

</html>