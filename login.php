<?php
session_start();
require("./connect.php");

// Check if form is submitted
if (isset($_POST['submit'])) {

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    // Get username and password from the form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Prepare the SQL statement to fetch user data
    $sql = "SELECT * FROM users_data WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();


    // Check if any record is fetched
    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Start session and redirect to dashboard

            $_SESSION['username'] = $username;
            if ($role == "employee") {
                header("Location: ./employer/dashboard.php");
                exit();
            } else {
                header("Location: ./candidate/dashboard.php");
                exit();
            }
        } else {
            echo "<script>
                        alert('Incorrect username or password. Please try again.');    
                    </script>";
        }
    } else {
        echo "<script>
                        alert('Incorrect username or password. Please try again.');    
                </script>";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

// CSRF Protection
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;


if (isset($_GET['role'])) {
    $uRole = $_GET['role'];
}

?>


<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login Page</title>
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
            background: linear-gradient(to right, #252e93, #7d83c2);
        }

        main {
            height: 80%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            background-color: rgb(191, 185, 176, 0.2);
            backdrop-filter: blur(5px);
            border-radius: 25px;
            color: #fff;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        main>section {
            width: calc(50% - 40px);
            height: 80%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        section img {
            width: 70%;
            height: auto;
            filter: grayscale(0.5);
        }

        .login-section {
            width: 80%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .login-section>div {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-section .title {
            font-size: 35px;
            font-weight: bold;
        }

        .login-section .info {
            width: 100%;
            font-size: 20px;
        }

        form {
            width: 80%;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        form>div {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        form .show-password {
            display: flex;
            flex-direction: row;
            justify-content: start;
            align-items: center;
        }

        form .show-password input {
            height: 20px;
            width: 20px;
        }

        form label {
            font-size: 18px;
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

        .login-section .forgot {
            width: 80%;
            display: flex;
            align-items: end;
            padding: 5px 10px;
        }

        .login-section .back-btn {
            width: 80%;
            display: flex;
            align-items: start;
            padding: 5px 10px;
        }

        .login-section .back-btn span a{
            background-color: #dedede;
            color: #000;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
    <!-- ==================== Icons link ==================== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css" />
</head>

<body>
    <main>
        <section>
            <img src="./assets/images/ima.png" alt="" />
        </section>

        <section class="login">
            <div class="login-section">
                <div class="title">Welcome back</div>
                <div class="info">
                    Don't have an account?
                    <a href="./registration.php?role=<?= $uRole ?? "" ?>">Create One</a>
                </div>
                <div>
                    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" onsubmit="return validateFun()">
                        <div>
                            <label for="email">Email</label>
                            <input type="email" name="username" id="email" />
                            <input type="hidden" name="role" id="role" value="<?= $uRole ?? "" ?>">
                        </div>
                        <div>
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" />
                        </div>
                        <div class="show-password">
                            <input type="checkbox" name="show_password" id="show_password" />
                            <label for="show_password">Show Password</label>
                        </div>

                        <!-- CSRF Token -->
                        <input type="hidden" name="csrf_token" value="<?= $token ?>">

                        <div>
                            <input type="submit" name="submit" value="Submit" />
                        </div>
                    </form>
                </div>

                <div class="forgot">
                    <a href="">Forgot password?</a>
                </div>

                <div class="back-btn">
                    <span><a href="./index.php">Back</a></span>
                </div>
            </div>
        </section>
    </main>

    <!-- Script for show hide password -->
    <script>
        let showPassword = document.getElementById("show_password");
        let password = document.getElementById("password");
        showPassword.addEventListener("click", function() {
            showPassword.checked ?
                (password.type = "text") :
                (password.type = "password");
        });


        function validateFun() {
            let email = document.getElementById("email").value;
            let password = document.getElementById("password").value;

            if (email === "" || password === "") {
                alert("Please fill out fields");
                return false;
            }

            return true;
        }

    </script>
</body>

</html>