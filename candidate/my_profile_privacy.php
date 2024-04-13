<?php 
    include("../connect.php");
    include("./session.php");

    $header_data = ['id' => 4, 'title' => 'Profile'];
    include("./main_header.php");

        
    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }


        // Sanitize and validate inputs
        $password = htmlspecialchars($_POST['password']);
        $new_pass = htmlspecialchars($_POST['new_password']);

        // Check if the entered current password matches the user's current password
        if(password_verify($password, $users_data['password'])) {
            // Hash the new password before updating in the database
            $hashed_password = password_hash($new_pass, PASSWORD_DEFAULT);

            // Prepare and execute the SQL statement to update the password
            $stmt = $conn->prepare("UPDATE users_data SET password = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $users_data['id']);
            if($stmt->execute()) {
                header("Location: ./my_profile.php");
                exit();
            } else {
                // Handle SQL execution error
                echo "Error updating password: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Current Password doesn't match. Please enter correct current password.";
        }
    }

    // CSRF Protection
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $token;

       
?>


    <!-- ======================== Main section ====================== -->

    <main id = "profile-section">
        <section class = "main-title">
            <a href="./my_profile.php">Home</a>    
            <a href="./my_profile_personal_info.php">Perfonal Info</a>    
            <a href="./my_profile_contact_info.php">Contact Info</a>    
            <a href="./my_profile_privacy.php">Privacy</a>    
        </section>
        
        <section class = "main-section">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $token ?>">

                <div>
                    <label for="password">Current Password</label>
                    <input type="text" name = "password" id="password" autocomplete="off">
                </div>

                <div>
                    <label for="new_password">New Password</label>
                    <input type="password" name = "new_password" id="new_password">
                </div>

                <div>
                    <label for="confirm_pass">Confirm Password</label>
                    <input type="password" name = "confirm_pass" id="confirm_pass">
                </div>

                <div>
                    <input type="reset" value="Reset">
                    <input type="submit" name = "submit" value="Submit">
                </div>

            </form>
        </section>
    </main>
    <!-- ========================= End Main ========================= -->

<?php require("../footer.php"); ?>