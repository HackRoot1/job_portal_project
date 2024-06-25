<?php 

include("../connect.php");
include("./session.php");
$sql = "SELECT * FROM users_data WHERE id = '{$users_data['id']}'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (isset($_POST['page_no'])) {
    $page_no = $_POST['page_no'];
    $page_start = ($page_no * 10);
    // // Retrieve user data from the database
    

    $sql2 = "SELECT * FROM applied_jobs WHERE candidate_id = '{$users_data['id']}' ORDER BY applied_time DESC LIMIT {$page_start}, 10";
}

$result2 = mysqli_query($conn, $sql2);
$num_rows = mysqli_num_rows($result2);

?>

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
                $row_no = (($page_no ? $page_no * 10 : "0") + 1);
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
                        <td><?php echo $row_no; ?></td>
                        <td><?php echo $data2['job_title'] ?? "Job Deleted."; ?></td>
                        <td><?php echo $company_name['company_name']; ?></td>
                        <td><?php echo $data2['job_location'] ?? "Job Deleted."; ?></td>
                        <td><?php echo $data2['min_job_exp'] ?? "Job Deleted." ?></td>
                        <td><?php echo floor($differenceTime / 86400) . " days ago"; ?></td>
                        <td><?php echo ($data['status'] == 1) ? "Cancelled" : "Pending" ?></td>
                        <td><?php echo ($data['status'] == 1) ? "<a href'#' class='disabled'>Cancelled</a>" : "<a href='cancel_job.php?job_id={$data['job_id']}'>Cancel</a>" ?></td>

                    </tr>

            <?php
                $row_no++;
                }
            } else {
                echo "<tr><td colspan='6'>No records found</td></tr>";
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