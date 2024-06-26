<?php
include("../connect.php");
include("./session.php");


$now_time = date(time());
// echo date(time());
// echo "<br>";
// echo date( "d-M-Y  h-i-s",$now_time);
// exit();
if (isset($_GET['job_id'])) {
    $job_id = $_GET['job_id'];
    $fetch_query = "SELECT * FROM posted_jobs WHERE id = ?";
    $stmt = $conn->prepare($fetch_query);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $result_query = $stmt->get_result();
    $fetched_data = $result_query->fetch_assoc();
    $stmt->close();
}

if (isset($_POST['submit']) || isset($_POST['update'])) {

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }


    $job_title = $_POST['job_title'];
    $job_desc = $_POST['job_desc'];
    $job_budget = $_POST['job_budget'];
    $job_type = $_POST['job_type'];
    $job_location = $_POST['job_location'];
    $employer_id = $_POST['employer_id'];
    $min_job_exp = $_POST['min_job_exp'];
    $max_job_exp = $_POST['max_job_exp'];
    $job_post_id = $_POST['job_id'];

    if (isset($_POST['submit'])) {
        $sql = "INSERT INTO 
                        posted_jobs
                        (employer_id, job_title, description, budget_ctc, job_type, job_location, min_job_exp, max_job_exp, posted_at, updated_at) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssiss", $employer_id, $job_title, $job_desc, $job_budget, $job_type, $job_location, $min_job_exp, $max_job_exp, $now_time, $now_time);
    } elseif (isset($_POST['update'])) {
        $sql = "UPDATE 
                        posted_jobs 
                    SET 
                        employer_id = ?, 
                        job_title = ?, 
                        description = ?, 
                        budget_ctc = ?, 
                        job_type = ?, 
                        job_location = ?, 
                        min_job_exp = ?, 
                        max_job_exp = ?,
                        updated_at = ?
                    WHERE 
                        id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssisi", $employer_id, $job_title, $job_desc, $job_budget, $job_type, $job_location, $min_job_exp, $max_job_exp, $now_time, $job_post_id);
    }
    if($stmt->execute()) {
        $stmt->close();
        header("Location: ./my_jobs.php");
        exit();
    }
    $stmt->close();
}

// CSRF Protection
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

$header_data = ['css' => 'post_new_job', 'active' => 2];
include("./header.php");

?>


        <section class="section-title">
            <span>Post New Jobs</span>
        </section>


        <section class="job-posting-section">
            <div class="job-form">
                <div class="wrapper">

                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <input type="hidden" name="employer_id" value="<?php echo $users_data['id']; ?>">
                        <input type="hidden" name="job_id" value="<?= $job_id ?? '' ?>">

                        <div class="flex-row">
                            <div class="flex-col">
                                <label for="job-title">Job Title</label>
                                <input type="text" name="job_title" id="job-title" value="<?= $fetched_data['job_title'] ?? "" ?>">
                            </div>
                            <div class="flex-col">
                                <label for="job-budget">Budget (CTC)</label>
                                <input type="text" name="job_budget" id="job-budget" value="<?= $fetched_data['budget_ctc'] ?? "" ?>">
                            </div>
                        </div>
                        <div class="flex-col">
                            <label for="job-desc">Job Description</label>
                            <textarea name="job_desc" id="job-desc" cols="30" rows="10"><?= $fetched_data['description'] ?? "" ?></textarea>
                        </div>
                        <div class="flex-row">
                            <div class="flex-col">
                                <label for="job-type">Job Type</label>
                                <!-- <input type="text"> -->
                                <select name="job_type" id="job-type">
                                    <option value="full-time" <?= (($fetched_data['job_type'] ?? "wrong") == "full-time") ? "selected" : "" ?>>Full Time</option>
                                    <option value="part-time" <?= (($fetched_data['job_type'] ?? "wrong") == "part-time") ? "selected" : "" ?>>Part Time</option>
                                </select>
                            </div>
                            <div class="flex-col">
                                <label for="job-location">Job Location</label>
                                <select name="job_location" id="job-location">
                                    <option value="mumbai" <?= ($fetched_data['job_location'] ?? "wrong") == 'mumbai' ? "selected" : "" ?>>Mumbai</option>
                                    <option value="pune" <?= ($fetched_data['job_location'] ?? "wrong") == 'pune' ? "selected" : "" ?>>Pune</option>
                                    <option value="banglore" <?= ($fetched_data['job_location'] ?? "wrong") == 'banglore' ? "selected" : "" ?>>Banglore</option>
                                    <option value="delhi" <?= ($fetched_data['job_location'] ?? "wrong") == 'delhi' ? "selected" : "" ?>>Delhi</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex-col">
                            <label for="min-exp">Min Exp.</label>
                            <input type="text" name="min_job_exp" id="min-exp" value="<?= $fetched_data['min_job_exp'] ?? "" ?>">
                        </div>
                        <div class="flex-col">
                            <label for="max-exp">Max Exp.</label>
                            <input type="text" name="max_job_exp" id="max-exp" value="<?= $fetched_data['max_job_exp'] ?? "" ?>">
                        </div>
                        <div class="flex-col">
                            <input type="submit" name="<?= isset($_GET['job_id']) ? 'update' : 'submit' ?>" value="Post">
                        </div>
                    </form>
                </div>

            </div>

        </section>

    </main>
</body>

</html>