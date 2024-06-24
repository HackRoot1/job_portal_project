<?php
include("../connect.php");
include("./session.php");

// Default SQL query to fetch all posted jobs
$sql = "SELECT * FROM posted_jobs";


// Check if search form is submitted
if (isset($_POST['search'])) {

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    // Initialize variables for job type and location
    $job_type = $_POST['job_type'] ?? "";
    $job_location = $_POST['job_location'] ?? "";

    // Prepare an array to store conditions
    $conditions = [];

    // Check if job type is provided
    if (!empty($job_type)) {
        // Sanitize job type input
        $job_type = mysqli_real_escape_string($conn, $job_type);
        // Add job type condition to the array
        $conditions[] = "job_type = '$job_type'";
    }

    // Check if job location is provided
    if (!empty($job_location)) {
        // Sanitize job location input
        $job_location = mysqli_real_escape_string($conn, $job_location);
        // Add job location condition to the array
        $conditions[] = "job_location = '$job_location'";
    }

    // If there are conditions, construct the WHERE clause
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
}

// Execute the SQL query
$result = mysqli_query($conn, $sql) or die("Query Failed");

// CSRF Protection
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

$header_data = ['css' => 'search_jobs', 'active' => 2];
include("./header.php");

?>



        <section class="filters-section">


            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                <input type="hidden" name="csrf_token" value="<?= $token ?>">

                <div>
                    <label for="job-type">Job Type:</label>
                    <select name="job_type" id="job-type">
                        <option value="">--- Select ---</option>
                        <option value="full-time">Full Time</option>
                        <option value="part-time">Part Time</option>
                        <option value="contract">Contract Based</option>
                    </select>
                </div>

                <div>
                    <label for="job_location">Job Location:</label>
                    <select name="job_location" id="job_location">
                        <option value="">--- Select ---</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="delhi">Delhi</option>
                        <option value="bangalore">Bangalore</option>
                    </select>
                </div>

                <div>
                    <input type="submit" name="search" value="Search">
                </div>
            </form>
        </section>



        <section class="result-table-data">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Job Title</th>
                        <th>Company Name</th>
                        <th>Job Location</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if (isset($result) && mysqli_num_rows($result) > 0) {
                        while ($data = mysqli_fetch_assoc($result)) {

                            // get company name using employer id 
                            $c = "SELECT company_name FROM users_data WHERE id = '{$data['employer_id']}'";
                            $r = mysqli_query($conn, $c);
                            $f = mysqli_fetch_assoc($r);
                    ?>
                            <tr>
                                <td><?php echo $data['id']; ?></td>
                                <td><a href="./searched_job.php?job_id=<?php echo $data['id']; ?>"><?php echo $data['job_title']; ?></a></td>
                                <td><?php echo $f['company_name']; ?></td>
                                <td><?php echo $data['job_location']; ?></td>
                            </tr>

                        <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center;">No records found.</td>
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