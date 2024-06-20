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

// Include header file
// $header_data = ['id' => 3, 'title' => 'Applied Employee Page', 'css' => 'my_jobs'];
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
        
        .sidebar .settings>div:hover a{
            color: #000;
        }
        .sidebar .settings>div:hover input{
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

        .filters-section>div {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .filters-section .tabs .tab-link {
            padding: 5px 20px;
            font-size: 18px;
            color: #cecece;
            cursor: pointer;
        }

        .filters-section .tabs .tab-link.active {
            color: #000;
            border-bottom: 3px solid #5d28d7;
        }

        .filters-section .sorts .sort {
            padding: 5px 10px;
            border-radius: 5px;
            background-color: #f0f0f0;
        }

        /* end */



        /* table design */

        .result-table-data {
            padding: 20px;
            height: 70vh;
            min-height: 50vh;
            overflow-y: auto;
        }

        .result-table-data table {
            width: 100%;
            border-collapse: collapse;
        }

        .result-table-data table thead tr {
            height: 40px;
            border: none;
            outline: none;
            background-color: #5d28d7;
            color: #fff;
        }

        .result-table-data table thead tr th {
            padding: 0 10px;
            font-size: 18px;
            font-weight: bold;
            text-align: start;
        }

        .result-table-data table tbody tr {
            height: 40px;
            border: none;
            outline: none;
        }

        .result-table-data table tbody tr:nth-child(even) {
            background-color: #f0f0f0;
        }

        .result-table-data table tbody tr td {
            padding: 0 10px;
            font-size: 18px;
            text-align: start;
        }

        
        .result-table-data table tbody tr td a {
            color: #000;
        }

        .result-table-data table tbody tr td .btns {
            background-color: var(--c);
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* end */


        .pagination {
            display: flex;
            justify-content: space-between;
            padding: 0 25px;
        }

        .pagination>div {
            display: flex;
            gap: 10px;
        }

        .pagination .display-pages>div {
            cursor: pointer;
            width: 15px;
            height: 15px;
        }
    </style>
</head>

<body>
    <!-- ======================== Main section ====================== -->

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
                <div class="tab-link">Active</div>
                <div class="tab-link active">Pending</div>
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