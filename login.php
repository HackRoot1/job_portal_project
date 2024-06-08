<?php 
    require("./connect.php");
    session_start();

    // Check if form is submitted
    if(isset($_POST['submit'])){
        
        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        // Get username and password from the form
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);
        $password = $_POST['password'];

        // Prepare the SQL statement to fetch user data
        $sql = "SELECT * FROM users_data WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if any record is fetched
        if($result->num_rows === 1){
            $row = $result->fetch_assoc();
            // Verify the password
            if(password_verify($password, $row['password'])){
                // Start session and redirect to dashboard
                session_start();
                $_SESSION['username'] = $username;
                if($role == "employee") {
                    header("Location: ./employer/dashboard.php");
                }else {
                    header("Location: ./candidate/dashboard.php");
                }
                exit();
            } else {
                echo "Incorrect username or password. Please try again.";
            }
        } else {
            echo "User not found. Please try again.";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }

    // CSRF Protection
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;


    if(isset($_GET['role'])) {
        $uRole = $_GET['role'];
    }

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Login Page</title>
    <!-- ================= header stylesheet ================= -->
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
            <div><a href="./index.php" class="active">Log In</a></div>
            <div><a href="./sign_up.php">Sign Up</a></div>
            <div><a href="#">Contact Us</a></div>
        </nav>

        <div class="header-title">Job Portal</div>
    </header>
    <!-- ======================= End Header ========================== -->






    <!-- ======================== Main section ====================== -->

    <main id = "login-page">
        
        <section class = "main-section">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

                <div>
                    <label for="username">Username</label>
                    <input type="text" name = "username" id = "username">
                    <input type="hidden" name = "role" id = "role" value = "<?= $uRole ?? "" ?>">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" name = "password" id = "password">
                </div>

                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $token ?>">

                <div>
                    <input type="reset" value="Reset">
                    <input type="submit" name = "submit" value="Submit">
                </div>

                <div>
                    <a href="./registration.php">
                        Create Account
                    </a>
                </div>

            </form>
        </section>
    </main>
    <!-- ========================= End Main ========================= -->


<?php require("./footer.php"); ?>
