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
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $date_of_birth = $_POST['date_of_birth']; // No need to escape as it's a date field

        // Validate date of birth format
        if(!strtotime($date_of_birth)) {
            echo "Invalid date of birth format. Please enter in YYYY-MM-DD format.";
            exit();
        }

        // Prepare and execute the update query
        $sql = "UPDATE users_data
                SET firstName = ?, lastName = ?, gender = ?, date_of_birth = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $firstName, $lastName, $gender, $date_of_birth, $users_data['id']);
        if($stmt->execute()) {
            header("Location: ./my_profile_personal_info.php");
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
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                

                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?= $token ?>">

                <div>
                    <label for="firstName">First Name</label>
                    <input type="text" name = "firstName" id="firstName" value="<?= $users_data['firstName'] ?? "" ?>">
                </div>

                <div>
                    <label for="lastName">Last Name</label>
                    <input type="text" name = "lastName" id="lastName" value="<?= $users_data['lastName'] ?? "" ?>">
                </div>

                <div>
                    <label for="company_name">Company Name</label>
                    <input type="text" name = "company_name" id="company_name" value="<?= $users_data['company_name'] ?? "" ?>">
                </div>
                              
                <div>
                    <label for="company_address">Company Address</label>
                    <input type="text" name = "company_address" id="company_address" value="<?= $users_data['company_address'] ?? "" ?>">
                </div>

                <div class = "gender-cell">
                    <label for="gender">Gender</label>
                    <div>  
                        <input type="radio" name="gender" value = "Male" id="male" <?= (($users_data['gender'] ?? "wrong") == "Male") ? "checked" : "" ?> ><label for="male">Male</label>
                        <input type="radio" name="gender" value = "Female" id="female" <?= (($users_data['gender'] ?? "wrong") == "Female") ? "checked" : "" ?> ><label for="female">Female</label>
                    </div>
                </div>

                <div>
                    <label for="dob">Date of Birth</label>
                    <input type="date" name="date_of_birth" id="dob" value="<?= $users_data['date_of_birth'] ?? "" ?>">
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