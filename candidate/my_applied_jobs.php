<?php
include("../connect.php");
include("./session.php");

// Get the user's ID from the session
$user_id = $users_data['id'] ?? null;

// Verify that the user is logged in
if (!$user_id) {
    // Redirect to the login page if the user is not logged in
    header("Location: ./login.php");
    exit();
}

// Retrieve user data from the database
$stmt = $conn->prepare("SELECT * FROM users_data WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

// Retrieve applied jobs for the user
$stmt2 = $conn->prepare("SELECT * FROM applied_jobs WHERE candidate_id = ? ORDER BY applied_time DESC");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

$header_data = ['css' => 'my_applied_jobs', 'active' => 3];
include("./header.php");

?>

        <section class="filters-section">
            <div class="tabs">
                <div class="tab-link active">All</div>
                <div class="tab-link">Pending</div>
                <div class="tab-link">Cancelled</div>
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
                        <th>Job Title</th>
                        <th>Company Name</th>
                        <th>Job Location</th>
                        <th>Min. Experience Required</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    date_default_timezone_set("Asia/Kolkata");

                    if (mysqli_num_rows($result2) > 0) {
                        while ($data = mysqli_fetch_assoc($result2)) {

                            $sql3 = "SELECT * FROM posted_jobs WHERE id = '{$data['job_id']}'";
                            $result3 = mysqli_query($conn, $sql3);
                            $data2 = mysqli_fetch_assoc($result3);

                            $todayDate = strtotime("now");
                            $differenceTime = $todayDate - strtotime($data['applied_time']);

                            // fetching company name 
                            if (isset($data2['employer_id'])) {
                                $company_name_query = "SELECT company_name FROM users_data WHERE id = '{$data2['employer_id']}'";
                                $company_name_result = mysqli_query($conn, $company_name_query);

                                $company_name = mysqli_fetch_assoc($company_name_result);
                            } else {
                                $company_name['company_name'] = "Job Deleted.";
                            }
                    ?>
                            <tr>
                                <td><?php echo $data['id']; ?></td>
                                <td><?php echo $data2['job_title'] ?? "Job Deleted."; ?></td>
                                <td><?php echo $company_name['company_name']; ?></td>
                                <td><?php echo $data2['job_location'] ?? "Job Deleted."; ?></td>
                                <td><?php echo $data2['min_job_exp'] ?? "Job Deleted." ?></td>
                                <td><?php echo floor($differenceTime / 86400) . " days ago"; ?></td>
                                <td><?php echo ($data['status'] == 1) ? "Cancelled" : "Pending" ?></td>
                                <td><?php echo ($data['status'] == 1) ? "<a href'#' class='disabled'>Cancelled</a>" : "<a href='cancel_job.php?job_id={$data['job_id']}'>Cancel</a>" ?></td>

                            </tr>

                    <?php
                        }
                    } else {
                        echo "<tr><td colspan='6'>No records found</td></tr>";
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