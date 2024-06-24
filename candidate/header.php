<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <script src="../assets/js/jQuery.js"></script>
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/<?= $header_data['css'] ?>.css">
</head>
<!-- ======================== Main section ====================== -->

<!-- ========================= End Main ========================= -->

<body>
    <section class="sidebar">
        <div class="menu">
            <div class="title">Something</div>
            <div class="tabs">
                <div class="tab-pills <?= $header_data['active'] == 1 ? "active" : "" ?>"><a href="./dashboard.php">Dashboard</a></div>
                <div class="tab-pills <?= $header_data['active'] == 2 ? "active" : "" ?>"><a href="./search_jobs.php">Search Jobs</a></div>
                <div class="tab-pills <?= $header_data['active'] == 3 ? "active" : "" ?>"><a href="./my_applied_jobs.php">My Applied Jobs</a></div>
            </div>
        </div>

        <div class="settings">
            <div class="<?= $header_data['active'] == 4 ? "active" : "" ?>"><a href="./my_profile.php">Settings</a></div>
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
                    <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "" ?>" alt="" />
                </div>
            </div>
        </nav>
