<?php 
    include("../connect.php");
    session_start();

        
    if(isset($_POST['submit'])){

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }


        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT * FROM users_data WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        if($result->num_rows == 1){
            // Fetch user data
            $row = $result->fetch_assoc();

            // Verify password
            if(password_verify($password, $row['password'])) {
                // Start session
                session_start();

                // Store username in session
                $_SESSION['username'] = $username;

                // Redirect to dashboard
                header("Location: ./dashboard.php");
                exit();
            } else {
                // Incorrect password
                echo "Incorrect username or password. Please try again.";
            }
        } else {
            // User not found
            echo "User not found. Please try again.";
        }

        // Close statement
        $stmt->close();
    }

    
    // CSRF Protection
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;


    // ======================= Header section ==================== 
    $header_data = ['id' => 2, 'title' => 'Candidate Log In Page']; 
    require('./header.php'); 
    // ======================= End Header ==========================
?>




    <!-- ======================== Main section ====================== -->

    <main id = "login-page">
        
        <section class = "main-section">
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

            
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $token ?>">

                <div>
                    <label for="username">Username</label>
                    <input type="text" name = "username" id = "username">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" name = "password" id = "password">
                </div>

                <div>
                    <input type="reset" value="Reset">
                    <input type="submit" name = "submit" value="Submit">
                </div>

                <div>
                    <a href="#">
                        Create Account
                    </a>
                </div>

            </form>
        </section>
    </main>
    <!-- ========================= End Main ========================= -->



<?php require("../footer.php"); ?>