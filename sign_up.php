<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
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
            <div><a href="#">Home</a></div>
            <div><a href="./index.php">Log In</a></div>
            <div><a href="./sign_up.php" class = "active">Sign Up</a></div>
            <div><a href="#" >Contact Us</a></div>
        </nav>
        <div class="header-title">Job Portal</div>
    </header>
    <!-- ======================= End Header ========================== -->


    
    <main>
        <section class = "main-title">
            <!-- Dynamically changed -->
            Sign Up
        </section>
        <section class = "main-section">
            <div class="box"><a href="./registration.php?role=employee">Employer</a></div>
            <div>Or</div>
            <div class="box"><a href="./registration.php?role=candidate">Individual</a></div>
        </section>
    </main>

<?php require("./footer.php"); ?>