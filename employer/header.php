<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $header_data['title'] ? $header_data['title'] : "No Title"; ?></title>
    <!-- ================= header and footer stylesheet ================= -->
    <link rel="stylesheet" href="../css/header.css">
    <!-- ================== main stylesheet ================= -->
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/<?= $header_data['css'] ?>.css">
    <!-- ==================== Icons link ==================== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    
    <!-- ======================= Header section ==================== -->
    <header>
        
        <nav>
            <div><a href="./dashboard.php" class = "<?= $header_data['id'] == 1 ? "active" : "" ?>" >Dashboard</a></div>
            <div><a href="./post_new_job.php" class = "<?= $header_data['id'] == 2 ? "active" : "" ?>" >Post New Job</a></div>
            <div><a href="./my_jobs.php" class = "<?= $header_data['id'] == 3 ? "active" : "" ?>" >My Jobs</a></div>
            <div><a href="./my_profile.php" class = "<?= $header_data['id'] == 4 ? "active" : "" ?>" >My Profile</a></div>
        </nav>

        <div class = "nav-section">
            <div class="header-title"><?php echo $users_data['company_name']; ?></div>
            <form method="post">
                <input type="submit" name = "logout" value = "Log Out">
            </form>
        </div>
    </header>
    <!-- ======================= End Header ========================== -->


