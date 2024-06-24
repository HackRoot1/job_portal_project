<?php
include("../connect.php");
include("./session.php");


if (isset($_POST['page_no'])) {
    $page_no = $_POST['page_no'];

    $page_start = ($page_no * 10);

    // Fetch jobs posted by the current employer
    $sql = "SELECT * FROM posted_jobs WHERE employer_id = '{$users_data['id']}' ORDER BY posted_at DESC, updated_at DESC LIMIT {$page_start}, 10";
    $result = mysqli_query($conn, $sql) or die("Query Failed: " . mysqli_error($conn));
}

// Fetch jobs posted by the current employer
$sql_num_row = "SELECT * FROM posted_jobs WHERE employer_id = '{$users_data['id']}' ORDER BY posted_at DESC, updated_at DESC";
$result_rows = mysqli_query($conn, $sql_num_row) or die("Query Failed: " . mysqli_error($conn));

$num_rows = mysqli_num_rows($result_rows);

?>

<section class="result-table-data">
    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Job Title</th>
                <th>Job Location</th>
                <th>Max CTC Budget</th>
                <th>Applications</th>
                <th>Posted At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($result) > 0) {
                $row_no = (($page_no ? $page_no * 10 : "0") + 1);
                while ($data = mysqli_fetch_assoc($result)) {

                    // count applications for each job posted.
                    $query = "SELECT count(job_id) as count_job FROM applied_jobs WHERE job_id = '{$data['id']}'";
                    $result2 = mysqli_query($conn, $query);
                    $count = mysqli_fetch_assoc($result2);
            ?>
                    <tr>
                        <td><?php echo $row_no; ?></td>
                        <td><?php echo $data['job_title']; ?></td>
                        <td><?php echo $data['job_location']; ?></td>
                        <td><?php echo $data['budget_ctc']; ?></td>

                        <!-- fetch data count of application using job_id -->
                        <td>
                            <a href="./applied_candidates.php?job_id=<?php echo $data['id']; ?>">
                                <?php echo $count['count_job']; ?>
                            </a>
                        </td>
                        <td><?= date( "d-M-Y h-i-s", ($data['posted_at'] >= $data['updated_at'] ? $data['posted_at'] : $data['updated_at'])) ?></td>
                        <td>
                            <a class="btns" style="--c: green" href="post_new_job.php?job_id=<?php echo $data['id']; ?>">Edit</a>
                            <a class="btns" style="--c: red" href="delete_job.php?job_id=<?php echo $data['id']; ?>">Delete</a>
                        </td>
                    </tr>

            <?php
            $row_no++;
                }
            }
            ?>
        </tbody>
    </table>
</section>


<section class="pagination">
    <div class="display-details">Showing <?= $page_start . "- ". ( $num_rows > $page_start ? ($num_rows) : $page_start + 10) ?> of <?= $num_rows ?></div>
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