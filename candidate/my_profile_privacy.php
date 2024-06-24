<?php 
    include("../connect.php");
    include("./session.php");

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

    $header_data = ['css' => 'profile_privacy', 'active' => 4];
    include("./header.php");
?>

        <section class="banner">

        </section>


        <section class="profile-section">
            <div>
                <div class="profile-info">
                    <div class="img">
                        <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "p3.jpg" ?>" id="previewImg" alt="">
                        <div class="name">Saurabh Damale</div>
                        <div class="c_name">Company Name</div>
                    </div>

                    <div class="info">Saurabh Damale</div>
                    <div class="info">Saurabh Damale</div>
                    <div class="info">Saurabh Damale</div>
                </div>

                <div class="profile-update-form">

                    <div class="tabs">
                        <div><a href="./my_profile.php">Home</a> </div>
                        <div><a href="./my_profile_personal_info.php">Perfonal Info</a> </div>
                        <div><a href="./my_profile_contact_info.php">Contact Info</a> </div>
                        <div class="active"><a href="./my_profile_privacy.php">Privacy</a> </div>
                    </div>


                    <div class="form">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?= $token ?>">

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="password">Current Password</label>
                                    <input type="text" name = "password" id="password" autocomplete="off">    
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name = "new_password" id="new_password" autocomplete="off">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label for="confirm_pass">Confirm Password</label>
                                    <input type="password" name = "confirm_pass" id="confirm_pass">
                                </div>
                            </div>

                            <div class="form-row">
                                <input type="submit" name="submit" value="Submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

</body>

</html>