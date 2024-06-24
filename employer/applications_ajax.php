<?php
include("../connect.php");
include("./session.php");


if (isset($_POST['page_no'])) {
    $page_no = $_POST['page_no'];
    $page_start = ($page_no * 10);

    // Fetch jobs posted by the current employer
    // Fetch applied employees for the specified job_id
    $sql = "SELECT * FROM applied_jobs WHERE emp_id = '{$users_data['id']}' LIMIT {$page_start}, 10";
    $result = mysqli_query($conn, $sql) or die("Query Failed");
}

if(isset($_POST['sortFilter'])) {
    $filter = $_POST['sortFilter'];
    $page_no = $_POST['page_no'];

    $page_start = ($page_no * 10);
    $sql = "SELECT * FROM applied_jobs WHERE emp_id = '{$users_data['id']}' AND job_id = '{$filter}' LIMIT {$page_start}, 10";
    $result = mysqli_query($conn, $sql) or die("Query Failed");
}


// Fetch jobs posted by the current employer
$sql_rows = "SELECT * FROM applied_jobs WHERE emp_id = '{$users_data['id']}' LIMIT {$page_start}, 10";
$result_rows = mysqli_query($conn, $sql_rows) or die("Query Failed");
$num_rows = mysqli_num_rows($result_rows);

?>


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
    <div class="display-details">Showing <?= $page_start . "- " . ($num_rows > $page_start ? ($num_rows) : $page_start + 10) ?> of <?= $num_rows ?></div>
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