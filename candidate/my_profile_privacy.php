<?php 
    include("../connect.php");
    include("./session.php");

    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        
        $current_password = $_POST['password'];
        $new_password = $_POST['new_password'];

        // Verify if the current password matches the one stored in the database
        if(password_verify($current_password, $users_data['password'])) {
            // Hash the new password before updating
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $sql = "UPDATE users_data SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $users_data['id']);

            if($stmt->execute()) {
                echo "Password updated successfully.";
            } else {
                echo "Failed to update password. Please try again.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Current Password doesn't match. Please enter the correct current password.";
        }
    }

    // CSRF Protection
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;

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


        .banner {
            background: url(../assets/images/cool-background.png);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            height: 200px;
            width: 100%;
        }

        .profile-section {
            padding: 0 30px;
        }

        .profile-section>div {
            padding: 0 30px;
            height: 300px;
            margin-top: -90px;
            display: flex;
            gap: 20px;
        }


        /* profile card */

        .profile-section .profile-info {
            background-color: #f0f0f0;
            border: 1px solid #000;
            height: 400px;
            width: 300px;
        }

        .profile-section .profile-info>div {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid;
        }

        .profile-section .profile-info .img {
            width: 100%;
            height: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .profile-section .profile-info .name {
            font-size: 25px;
            font-weight: bold;
        }

        .profile-section .profile-info .c_name {
            font-size: 16px;
        }

        .profile-section .profile-info .img img {
            height: 100px;
            width: 100px;
            border-radius: 50%;
        }

        .profile-section .profile-info .info {
            font-size: 18px;
            height: 35px;
        }

        /* end */


        /* form */
        .profile-update-form {
            background-color: #f0f0f0;
            border: 1px solid #000;
            min-height: 480px;
            width: 100%;
        }

        .profile-update-form .tabs {
            height: 70px;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 20px;
            font-size: 18px;
        }

        .profile-update-form .tabs a {
            color: #000;
        }

        .profile-update-form .tabs>div {
            cursor: pointer;
        }

        .profile-update-form .tabs>div.active {
            border-bottom: 3px solid #5d28d7;
            font-weight: bold;
            padding: 10px 0;

        }

        .profile-update-form .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-update-form .form form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-update-form .form form .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            gap: 20px;
        }

        .profile-update-form .form form .form-row .form-col {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 50%;
        }

        .profile-update-form .form form .form-row .form-col.gender-col {
            display: flex;
            flex-direction: row;
            justify-content: start;
            align-items: center;
        }

        .profile-update-form .form form .form-row .form-col .gender-cell {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .profile-update-form .form form .form-row .form-col input {
            height: 40px;
            border: none;
            outline: none;
            padding: 0 15px;
            border-radius: 5px;
        }

        .profile-update-form .form form .form-row input[type='submit'] {
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
    <!-- ======================== Main section ====================== -->
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
            <div class="title">Dashboard</div>

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

        <section class="banner">

        </section>


        <section class="profile-section">
            <div>
                <div class="profile-info">
                    <div class="img">
                        <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "p3.jpg" ?>" id="previewImg" alt="">
                        <div class="name">Saurabh Damale</div>
                        <div class="c_name">Company Name</div>
                    </div>

                    <div class="info">Saurabh Damale</div>
                    <div class="info">Saurabh Damale</div>
                    <div class="info">Saurabh Damale</div>
                </div>

                <div class="profile-update-form">

                    <div class="tabs">
                        <div><a href="./my_profile.php">Home</a> </div>
                        <div><a href="./my_profile_personal_info.php">Perfonal Info</a> </div>
                        <div><a href="./my_profile_contact_info.php">Contact Info</a> </div>
                        <div class="active"><a href="./my_profile_privacy.php">Privacy</a> </div>
                    </div>


                    <div class="form">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?= $token ?>">

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="password">Current Password</label>
                                    <input type="text" name = "password" id="password" autocomplete="off">    
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name = "new_password" id="new_password" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label for="confirm_pass">Confirm Password</label>
                                    <input type="password" name = "confirm_pass" id="confirm_pass">
                                </div>
                            </div>

                            <div class="form-row">
                                <input type="submit" name="submit" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>