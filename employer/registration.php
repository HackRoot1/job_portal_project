<?php 
    // Starting connection
    include("../connect.php");

    session_start();
    

    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        // Store form data in variables after escaping
        $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
        $company_address = mysqli_real_escape_string($conn, $_POST['company_address']);
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $c_no = mysqli_real_escape_string($conn, $_POST['c_no']);
        $current_location = mysqli_real_escape_string($conn, $_POST['current_location']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $role = mysqli_real_escape_string($conn, $_POST['role']);

        // Validate email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format");
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and execute the insert query using prepared statement
        $sql = "INSERT INTO 
                    users_data(
                        firstName,
                        lastName,
                        gender,
                        contact_no,
                        current_location,
                        company_name,
                        company_address,
                        username, 
                        email, 
                        password, 
                        role
                    ) 
                VALUES 
                    (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $firstName, $lastName, $gender, $c_no, $current_location, $company_name, $company_address, $username, $email, $hashed_password, $role);
        $stmt->execute();
        $stmt->close();

        // Optionally, you can provide a success message
        echo "User registered successfully.";

        // Closing connection
        mysqli_close($conn);
    }

    // CSRF Protection
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Registration form</title>
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">
    <!-- ================= header and footer stylesheet ================= -->
    <link rel="stylesheet" href="../css/header.css">
    <!-- ================== main stylesheet ================= -->
    <link rel="stylesheet" href="../css/main.css">    
</head>
<body>
        

    <!-- ======================= Header section ==================== -->
    <header>
        
        <nav>
            <div><a href="#">Home</a></div>
            <div><a href="../index.php">Log In</a></div>
            <div><a href="../sign_up.php" class="active">Sign Up</a></div>
            <div><a href="#">Contact Us</a></div>
        </nav>
        <div class="header-title">Job Portal</div>
    </header>
    <!-- ======================= End Header ========================== -->





    <!-- Main -->
    <main id = "registration-section">
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">

            <!-- CSRF Token -->
            <input type="hidden" name="csrf_token" value="<?= $token ?>">

            <div>
                <label for="company_name">Company Name</label>
                <input type="text" name = "company_name" id="company_name"> <span>lakhs</span>
            </div>
            <div>
                <label for="company_address">Company Address</label>
                <input type="text" name = "company_address" id="company_address"> <span>lakhs</span>
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

            <input type="hidden" name="role" value="employer">

            <div>
                <input type="reset" value="Reset">
                <input type="submit" name = "submit" value="Submit">
            </div>
        </form>

    </main>


<?php require("../footer.php"); ?>