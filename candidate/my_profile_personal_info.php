<?php 
    include("../connect.php");
    include("./session.php");

    $header_data = ['id' => 4, 'title' => 'Profile'];
    include("./main_header.php");
    
    // ===================== Update form data=========================
    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }


        // Sanitize and validate inputs
        $username = htmlspecialchars($_POST['username']);
        $firstName = htmlspecialchars($_POST['firstName']);
        $lastName = htmlspecialchars($_POST['lastName']);
        $gender = htmlspecialchars($_POST['gender']);
        $date_of_birth = htmlspecialchars($_POST['date_of_birth']);

        // Prepare the SQL statement
        $sql = "UPDATE users_data SET ";

        // Update profile image if provided
        if(isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
            $file_name = $_FILES['profile_img']['name'];
            $file_tmp_name = $_FILES['profile_img']['tmp_name'];
            $file_destination = "../assets/images/" . $file_name;

            // Move the uploaded file to destination directory
            if(move_uploaded_file($file_tmp_name, $file_destination)) {
                $sql .= "profile_pic = '{$file_name}', ";
            } else {
                // Handle file upload error
                echo "File upload failed.";
            }
        }

        // Append other fields to the SQL statement
        $sql .= "username = ?, firstName = ?, lastName = ?, gender = ?, date_of_birth = ? WHERE id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sssssi", $username, $firstName, $lastName, $gender, $date_of_birth, $users_data['id']);

        // Execute the statement
        if($stmt->execute()) {
            header("Location: ./my_profile_personal_info.php");
            exit();
        } else {
            // Handle SQL execution error
            echo "Error updating record: " . $stmt->error;
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
                    <label for="preview">Preview Image</label>
                    <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "" ?>" id="previewImg" alt="Preview Image" style="max-width: 300px; max-height: 300px;">
                </div>

                <div>
                    <label for="profile-img">Profile Image</label>
                    <input type="file" name="profile_img" id="profile-img" accept="image/*">
                </div>
                
                <div>
                    <label for="username">Username</label>
                    <input type="text" name = "username" id="username" value="<?= $users_data['username'] ?? "" ?>">
                </div>

                <div>
                    <label for="firstName">First Name</label>
                    <input type="text" name = "firstName" id="firstName" value="<?= $users_data['firstName'] ?? "" ?>">
                </div>

                <div>
                    <label for="lastName">Last Name</label>
                    <input type="text" name = "lastName" id="lastName" value="<?= $users_data['lastName'] ?? "" ?>">
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


<!-- ============== Script for preview Image ================== -->
                
<script>
    // Get reference to the input field and the preview image element
    const uploadInput = document.getElementById('profile-img');
    const previewImg = document.getElementById('previewImg');

    // Add event listener to the input field
    uploadInput.addEventListener('change', function() {
    // Check if any file is selected
    if (uploadInput.files && uploadInput.files[0]) {
        // Create a FileReader object
        const reader = new FileReader();

        // Set the image file to be read by the FileReader
        reader.readAsDataURL(uploadInput.files[0]);

        // Set up the onload event to set the src attribute of the preview image
        reader.onload = function(e) {
        previewImg.src = e.target.result; // Set the src attribute with the data URL
        previewImg.style.display = 'block'; // Make the preview image visible
        };
    }
    });

</script>


<?php require("../footer.php"); ?>