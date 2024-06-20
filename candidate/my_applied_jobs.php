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

?>


<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Jobs Page</title>

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
    <section class="sidebar">
        <div class="menu">
            <div class="title">Something</div>
            <div class="tabs">
                <div class="tab-pills"><a href="./dashboard.php">Dashboard</a></div>
                <div class="tab-pills"><a href="./search_jobs.php">Search Jobs</a></div>
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
            <div class="title">Employer Dashboard</div>

            <div class="links">
                <div class="search">
                    <input type="search" name="" id="" />
                </div>
                <div class="profile">
                    <span>Profile</span>
                    <img src="../assets/images/p3.jpg" alt="" />
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
                        <th>Job Title</th>
                        <th>Company Name</th>
                        <th>Job Location</th>
                        <th>Min. Experience Required</th>
                        <th>Applied On</th>
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
                                <td><?php echo ($data['status'] == 1) ? "Cancelled" : "<a href='cancel_job.php?job_id={$data['job_id']}'>Cancel Application</a>" ?></td>

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