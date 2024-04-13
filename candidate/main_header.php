
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $header_data['title'] ?? "No Title" ?></title>
    <!-- ================= header stylesheet ================= -->
    <link rel="stylesheet" href="../css/header.css">
    <!-- ================== main stylesheet ================= -->
    <link rel="stylesheet" href="../css/main.css">
    <!-- ==================== Icons link ==================== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>
    
    <!-- ======================= Header section ==================== -->
    <header>
        
        <nav>
            <div><a href="./dashboard.php" class = "<?= $header_data['id'] == 1 ? "active" : "" ?>">Dashboard</a></div>
            <div><a href="./search_jobs.php" class = "<?= $header_data['id'] == 2 ? "active" : "" ?>">Search Jobs</a></div>
            <div><a href="./my_applied_jobs.php" class = "<?= $header_data['id'] == 3 ? "active" : "" ?>">My Applied Jobs</a></div>
            <div><a href="./my_profile.php" class = "<?= $header_data['id'] == 4 ? "active" : "" ?>">My Profile</a></div>
        </nav>

        <div class = "nav-section">
            <div class="header-title"><?php echo $users_data['firstName']; ?></div>
            <form method="post">
                <input type="submit" name = "logout" value = "Log Out">
            </form>
        </div>
    </header>
    <!-- ======================= End Header ========================== -->
