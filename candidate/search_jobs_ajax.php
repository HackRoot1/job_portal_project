<?php

include("../connect.php");
include("./session.php");

$sql_rows = "SELECT * FROM posted_jobs";

if (isset($_POST['page_no'])) {
    $page_no = $_POST['page_no'];
    $page_start = ($page_no * 10);

    // Fetch jobs posted by the current employer
    // Fetch applied employees for the specified job_id
    $sql = "SELECT * FROM posted_jobs";
    
    if (isset($_POST['job_type']) && isset($_POST['job_location'])) {
        // Validate CSRF token
        if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            die("CSRF token validation failed.");
        }

        // Initialize variables for job type and location
        $job_type = $_POST['job_type'];
        $job_location = $_POST['job_location'];

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
            $sql_rows .= " WHERE " . implode(" AND ", $conditions);
        }
    }

    $sql .= " LIMIT {$page_start}, 10";
    $result = mysqli_query($conn, $sql) or die("Query Failed" . mysqli_error($conn));

}


// Fetch jobs posted by the current employer

$result_rows = mysqli_query($conn, $sql_rows) or die("Query Failed");
$num_rows = mysqli_num_rows($result_rows);

?>

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
                $row_no = (($page_no ? $page_no * 10 : "0") + 1);
                while ($data = mysqli_fetch_assoc($result)) {

                    // get company name using employer id 
                    $c = "SELECT company_name FROM users_data WHERE id = '{$data['employer_id']}'";
                    $r = mysqli_query($conn, $c);
                    $f = mysqli_fetch_assoc($r);
            ?>
                    <tr>
                        <td><?php echo $row_no; ?></td>
                        <td><a href="./searched_job.php?job_id=<?php echo $data['id']; ?>"><?php echo $data['job_title']; ?></a></td>
                        <td><?php echo $f['company_name']; ?></td>
                        <td><?php echo $data['job_location']; ?></td>
                    </tr>

                <?php
                    $row_no++;
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
    <div class="display-details">Showing <?= ($page_start + 1) . "- " . ($num_rows <= $page_start + 10 ? ($num_rows) : $page_start + 10) ?> of <?= $num_rows ?></div>
    <div class="display-pages">
        <?php
        if ($num_rows > 10) {
            $div = floor($num_rows / 10);
            $rem = $num_rows % 10;
            if ($rem !== 0) {
                $div += 1;
            }

            for ($i = 0; $i < $div; $i++) {
        ?>
                <div class="page" data-pageid="<?= $i ?>"><?= $i + 1 ?></div>
            <?php } ?>
        <?php } else { ?>
            <div class="page" data-pageid="0">1</div>
        <?php } ?>
    </div>
</section>