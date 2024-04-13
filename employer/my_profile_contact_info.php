<?php 
    include("../connect.php");
    include("./session.php");

    $header_data = ['id' => 4, 'title' => 'Profile'];
    require('./header.php');

    // Update form data if the form is submitted
    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        // Sanitize and validate input data
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $contact_no = filter_var($_POST['c_no'], FILTER_SANITIZE_STRING);
        $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
        $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

        // Validate email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email address.";
            exit();
        }

        // Prepare and execute the update query
        $sql = "UPDATE users_data
                SET email = ?, contact_no = ?, current_location = ?, address = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $email, $contact_no, $location, $address, $users_data['id']);
        if($stmt->execute()) {
            // Redirect to the profile page after successful update
            header("Location: ./my_profile_contact_info.php");
            exit();
        } else {
            echo "Failed to update profile information.";
        }

        // Close statement
        $stmt->close();
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
                    <label for="email">E-mail</label>
                    <input type="text" name = "email" id="email" value="<?= $users_data['email'] ?? "" ?>">
                </div>

                <div>
                    <label for="c_no">Contact No</label>
                    <input type="text" name = "c_no" id="c_no" value="<?= $users_data['contact_no'] ?? "" ?>">
                </div>

                <div>
                    <label for="location">Current Location</label>
                    <input type="text" name = "location" id="location" value="<?= $users_data['current_location'] ?? "" ?>">
                </div>

                <div>
                    <label for="address">Address</label>
                    <input type="text" name = "address" id="address" value="<?= $users_data['address'] ?? "" ?>">
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