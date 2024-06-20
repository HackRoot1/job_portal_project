<?php
include("../connect.php");
include("./session.php");

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
                        (employer_id, job_title, description, budget_ctc, job_type, job_location, min_job_exp, max_job_exp) 
                    VALUES 
                        (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssi", $employer_id, $job_title, $job_desc, $job_budget, $job_type, $job_location, $min_job_exp, $max_job_exp);
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
                        max_job_exp = ? 
                    WHERE 
                        id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssssii", $employer_id, $job_title, $job_desc, $job_budget, $job_type, $job_location, $min_job_exp, $max_job_exp, $job_post_id);
    }
    $stmt->execute();
    $stmt->close();
}

// CSRF Protection
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

// $header_data = ['id' => 2, 'title' => isset($_GET['job_id']) ? 'Update Job' : 'Post New Job', 'css' => 'post_new_job'];
// require('./header.php');
?>

<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: row;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .sidebar {
            background-color: #5d28d7;
            width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
        }

        .sidebar .menu {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar .menu .title {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
            font-size: 25px;
        }

        .sidebar .menu .tabs {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .sidebar .menu .tab-pills {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 30px;
            height: 40px;
            width: 80%;
            color: #fff;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
        }

        .sidebar .menu .tab-pills:hover {
            color: #000;
            background-color: aliceblue;
            cursor: pointer;
        }

        .sidebar .menu .tab-pills:hover a {
            color: #000;
        }

        .sidebar .settings {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
            align-items: center;
            justify-content: center;
        }

        .sidebar .settings>div {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 80%;
            color: #fff;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
        }

        .sidebar .settings>div input {
            background-color: transparent;
            border: none;
            outline: none;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 18px;
            font-family: serif;
            color: #fff;
            cursor: pointer;
        }

        .sidebar .settings>div:hover {
            color: #000;
            background-color: aliceblue;
            cursor: pointer;
        }

        .sidebar .settings>div:hover a {
            color: #000;
        }

        .sidebar .settings>div:hover input {
            color: #000;
        }

        /* navbar design */
        nav {
            width: calc(100vw - 250px);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 70px;
            background-color: #f0f0f0;
            /* position: fixed; */
        }

        nav .title {
            font-size: 28px;
            font-weight: bold;
        }

        nav .links {
            height: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        nav .links .search input {
            height: 40px;
            border-radius: 5px;
            outline: none;
            border: none;
            width: 250px;
            padding: 0 20px;
            font-size: 16px;
        }

        nav .links .profile {
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            background-color: #5d28d7;
            padding: 0px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        nav .links .profile img {
            width: 30px;
            height: 30px;
            border-radius: 50px;
        }

        /* end */

        /* title */

        .section-title {
            padding: 20px 30px;
        }

        .section-title span {
            font-size: 25px;
            font-weight: bold;
        }

        /* end */



        .job-posting-section {
            display: flex;
            padding: 0px 30px;
            width: 100%;
        }

        .job-form {
            width: 100%;
            height: 80%;
            background-color: #f0f0f0;
            padding: 20px;
        }

        .job-form .wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .job-form form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .job-form form .flex-row {
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 20px;
        }

        .job-form form .flex-col {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .job-form form .flex-col input,
        select,
        textarea {
            height: 35px;
            outline: none;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
        }

        textarea {
            height: 50px;
        }

        .job-form form .flex-col input[type='submit'] {
            height: 40px;
            border: none;
            outline: none;
            padding: 0 15px;
            border-radius: 5px;
            background-color: #5d28d7;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        /* end */
    </style>
</head>

<body>



    <section class="sidebar">
        <div class="menu">
            <div class="title">Something</div>
            <div class="tabs">
                <div class="tab-pills"><a href="./dashboard.php">Dashboard</a></div>
                <div class="tab-pills"><a href="./post_new_job.php">Post New Jobs</a></div>
                <div class="tab-pills"><a href="./my_jobs.php">My Posted Jobs</a></div>
                <div class="tab-pills"><a href="">Applications</a></div>
            </div>
        </div>

        <div class="settings">
            <div><a href="./my_profile.php">Settings</a></div>
            <div>
                <form method="post">
                    <input type="submit" name="logout" value="Logout">
                </form>
            </div>
        </div>
    </section>

    <!-- ======================== Main section ====================== -->


    <main>
        <nav>
            <div class="title">Dashboard</div>

            <div class="links">
                <div class="search">
                    <input type="search" name="" id="" />
                </div>
                <div class="profile">
                    <span>Profile</span>
                    <img src="./assets/images/p3.jpg" alt="" />
                </div>
            </div>
        </nav>

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