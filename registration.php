<?php 
    // starting connection
    include("./connect.php");
    session_start();
      

    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        // Storing form data in variables after sanitization
        $current_ctc = htmlspecialchars($_POST['current_ctc']);
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $gender = htmlspecialchars($_POST['gender']);
        $dob = htmlspecialchars($_POST['dob']);
        $c_no = htmlspecialchars($_POST['c_no']);
        $current_location = htmlspecialchars($_POST['current_location']);
        $email = htmlspecialchars($_POST['email']);
        $username = htmlspecialchars($_POST['username']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
        $role = htmlspecialchars($_POST['role']);

        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO users_data (firstName, lastName, gender, contact_no, current_location, current_ctc, date_of_birth, username, email, password, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $firstName, $lastName, $gender, $c_no, $current_location, $current_ctc, $dob, $username, $email, $password, $role);

        // Execute the statement
        if($stmt->execute()) {
            // Registration successful, redirect to login page or dashboard
            header("Location: ./login.php");
            exit();
        } else {
            // Registration failed, handle the error
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
    
    // Close the database connection
    mysqli_close($conn);

    
    // CSRF Protection
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;


    // $header_data = ['id' => 3, 'title' => 'Candidate Registration In Page'];    
    // require('./header.php'); 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Registration In Page</title>
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
            <div><a href="./index.php">Log In</a></div>
            <div><a href="./sign_up.php" class = "active">Sign Up</a></div>
            <div><a href="#">Contact Us</a></div>
        </nav>

        <div class="header-title">Job Portal</div>
    </header>
    <!-- ======================= End Header ========================== -->

    <!-- ======================= End Header ========================== -->






    <!-- ======================== Main section ====================== -->

    <main id = "registration-section">
        
        <section class = "main-section">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $token ?>">

                <div>
                    <label for="current-ctc">Current CTC</label>
                    <input type="text" name = "current_ctc" id = "current-ctc"><span>lakhs</span>
                </div>
                
                <div>
                    <label for="firstName">First Name</label>
                    <input type="text" name = "firstName" id="firstName">
                </div>

                <div>
                    <label for="lastName">Last Name</label>
                    <input type="text" name = "lastName" id="lastName">
                </div>

                <div class = "gender-cell">
                    <label for="gender">Gender</label>
                    <div>  
                        <input type="radio" name="gender" value = "Male" id="male"><label for="male">Male</label>
                        <input type="radio" name="gender" value = "Female" id="female"><label for="female">Female</label>
                    </div>
                </div>

                <div>
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="dob" id="dob">
                </div>

                <div>
                    <label for="c_no">Contact Number</label>
                    <input type="text" name = "c_no" id="c_no">
                </div>

                <div>
                    <label for="current_location">Current Location</label>
                    <input type="text" name = "current_location" id="current_location">
                </div>

                <div>
                    <label for="email">Email Id</label>
                    <input type="email" name = "email" id="email">
                </div>

                <div>
                    <label for="username">Username</label>
                    <input type="text" name = "username" id="username">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" name = "password" id="password">
                </div>

                <div>
                    <label for="re_password">Confirm Password</label>
                    <input type="password" id="re_password">
                </div>

                <div class="checkbox-container">
                    <input type="checkbox" name="condition" id="condition">
                    <label for="condition">I Agree to the Terms and Conditions.</label>
                </div>

                <input type="hidden" name="role" value="candidate">

                <div>
                    <input type="reset" value="Reset">
                    <input type="submit" name = "submit" value="Submit">
                </div>

            </form>
        </section>
    </main>
    <!-- ========================= End Main ========================= -->


<?php require("./footer.php"); ?>