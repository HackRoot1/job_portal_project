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
            background-color: aliceblue;
            cursor: pointer;
        }

        .sidebar .menu .tab-pills.active {
            color: #000;
            background-color: aliceblue;
            cursor: pointer;
        }
        .sidebar .menu .tab-pills.active a {
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

        .sidebar .settings>div:hover {
            color: #000;
            background-color: aliceblue;
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

        /* filters section */

        .filters-section {
            width: calc(100vw - 250px);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 70px;
            /* background-color: #f0f0f0; */
        }

        .filters-section>.tabs {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        /* end */

        .display-job-data {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            padding: 0 20px;
        }

        .display-job-data .table {
            padding: 50px 0px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
            gap: 20px;
            background-color: #f0f0f0;
        }

        .display-job-data .table .row {
            display: flex;
            height: 30px;
            font-size: 20px;
            /* justify-content: center; */
            align-items: center;
            gap: 20px;
            width: 50%;
        }
        
        .display-job-data .table .row .apply-btn a{
            width: 100px;
            height: 30px;
            background-color: #5d28d7;
            padding: 5px 10px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <section class="sidebar">
        <div class="menu">
            <div class="title">Something</div>
            <div class="tabs">
                <div class="tab-pills"><a href="./dashboard.php">Dashboard</a></div>
                <div class="tab-pills active"><a href="./search_jobs.php">Search Jobs</a></div>
                <div class="tab-pills"><a href="./my_applied_jobs.php">My Applied Jobs</a></div>
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