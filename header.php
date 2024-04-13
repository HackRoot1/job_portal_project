<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $header_data['title']; ?></title>
    <!-- ================= header and footer stylesheet ================= -->
    <link rel="stylesheet" href="./css/header.css">
    <!-- ================== main stylesheet ================= -->
    <link rel="stylesheet" href="./css/main.css">
    <!-- ==================== Icons link ==================== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
</head>
<body>

    <!-- ======================= Header section ==================== -->
    <header>
        <nav>
            <div><a href="#" class = "<?= $header_data['id'] == 1 ? "active" : "" ?>">Home</a></div>
            <div><a href="./index.php" class = "<?= $header_data['id'] == 2 ? "active" : "" ?>">Log In</a></div>
            <div><a href="./sign_up.php" class = "<?= $header_data['id'] == 3 ? "active" : "" ?>">Sign Up</a></div>
            <div><a href="#" class = "<?= $header_data['id'] == 4 ? "active" : "" ?>">Contact Us</a></div>
        </nav>
        <div class="header-title">Job Portal</div>
    </header>
    <!-- ======================= End Header ========================== -->


    
    <main>
        <section class = "main-title">
            <!-- Dynamically changed -->
            <?= $header_data['main-title']; ?>
        </section>
        <section class = "main-section">
            <div class="box"><a href="./employer/<?= $header_data['link']; ?>.php">Employer</a></div>
            <div>Or</div>
            <div class="box"><a href="./candidate/<?= $header_data['link']; ?>.php">Individual</a></div>
        </section>
    </main>