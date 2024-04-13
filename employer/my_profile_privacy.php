<?php 
    include("../connect.php");
    include("./session.php");

    $header_data = ['id' => 4, 'title' => 'Profile'];
    require('./header.php');

    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        
        $current_password = $_POST['password'];
        $new_password = $_POST['new_password'];

        // Verify if the current password matches the one stored in the database
        if(password_verify($current_password, $users_data['password'])) {
            // Hash the new password before updating
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $sql = "UPDATE users_data SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $users_data['id']);

            if($stmt->execute()) {
                echo "Password updated successfully.";
            } else {
                echo "Failed to update password. Please try again.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Current Password doesn't match. Please enter the correct current password.";
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