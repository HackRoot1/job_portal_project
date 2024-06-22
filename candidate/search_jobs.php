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

        .filters-section > form {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            width: 100%;
        }

        .filters-section div {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        
        .filters-section div label{
            width: 200px;
        }
        
        .filters-section div input[type='submit']{
            height: 35px;
            width: 100px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-family: serif;
            font-size: 18px;
            color: #fff;
            background-color: #5d28d7;
            cursor: pointer;
        }
       
        .filters-section div select{
            height: 35px;
            width: 100%;
            border-radius: 5px;
            padding: 0 10px;
            outline: none;

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
            <div class="title">Job Search</div>

            <div class="links">
                <div class="search">
                    <input type="search" name="" id="" />
                </div>
                <div class="profile">
                    <span>Profile</span>
                    <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "" ?>" alt="" />
                </div>
            </div>
        </nav>


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