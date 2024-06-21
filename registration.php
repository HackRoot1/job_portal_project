<?php
// starting connection
include("./connect.php");
session_start();


if (isset($_POST['submit'])) {

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    // Storing form data in variables after sanitization
    $email = htmlspecialchars($_POST['email']);
    $firstName = htmlspecialchars($_POST['firstName']);
    $lastName = htmlspecialchars($_POST['lastName']);
    $company_name = htmlspecialchars($_POST['company_name']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $role = htmlspecialchars($_POST['role']);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO users_data (firstName, lastName,company_name, email, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstName, $lastName, $company_name, $email, $password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful, redirect to login page or dashboard
        header("Location: ./login.php?role=$role");
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

if (isset($_GET['role'])) {
    $uRole = $_GET['role'];
}

?>


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            height: 100dvh;
            width: 100dvw;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(to right, #252e93 60%, #7d83c2);
            color: #fff;
        }

        main {
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 25px;
        }

        main>section {
            width: 50%;
            height: 95%;
        }

        a {
            text-decoration: none;
            color: #fff;
        }



        .illustration {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .illustration>div {
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 20px;
            background-color: #dedede;
        }

        section img {
            width: 70%;
            height: auto;
            filter: grayscale(0.5);
        }


        .registration-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .registration-section>div {
            width: 60%;
        }

        .registration-section .title {
            font-size: 35px;
        }

        .registration-section .info {
            font-size: 20px;
        }

        .registration-section .form-info form {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .registration-section .form-info form>div {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .registration-section .form-info form>.special {
            display: flex;
            flex-direction: row;
            gap: 20px;
        }

        .registration-section .form-info form>.special>div {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        form label {
            font-size: 18px;
            color: #fff;
        }

        form input {
            height: 35px;
            width: 100%;
            outline: none;
            border-radius: 5px;
            padding: 5px 10px;
            border: none;
            background-color: #dedede;
        }

        form input[type="submit"] {
            background-color: #0f23ff;
            font-size: 18px;
            color: #dedede;
            font-weight: 400;
            cursor: pointer;
        }

        .registration-section .registration-btn {
            display: flex;
            gap: 10px;
        }

        .registration-section .back-btn {
            display: flex;
            align-items: start;
        }

        .registration-section .back-btn span a{
            background-color: #dedede;
            color: #000;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <!-- ======================== Main section ====================== -->

    <main>
        <section class="registration-section">

            <div class="title">Register</div>
            <div class="info">Enter your details to Register on the platform</div>

            <div class="form-info">
                <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
                    <div>
                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">
                        <input type="hidden" name="role" id="role" value="<?= $uRole ?? "" ?>">
                    </div>

                    <div>
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email">
                    </div>

                    <div class="special">
                        <div>
                            <label for="fname">First Name</label>
                            <input type="text" name="firstName" id="fname">
                        </div>
                        <div>
                            <label for="lname">Last Name</label>
                            <input type="text" name="lastName" id="lname">
                        </div>
                    </div>
                    <div>
                        <label for="c_name">Company Name</label>
                        <input type="text" name="company_name" id="c_name">
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password">
                    </div>
                    <div>
                        <input type="submit" name="submit" value="Submit">
                    </div>

                </form>
            </div>

            <div class="registration-btn">
                <span>Already Have an account? </span>
                <a href="./login.php">Log In</a>
            </div>

            <div class="back-btn">
                <span><a href="./index.php">Back</a></span>
            </div>
        </section>

        <section class="illustration">
            <div>
                <img src="./assets/images/ima.png" alt="">
            </div>
        </section>
    </main>
</body>

</html>