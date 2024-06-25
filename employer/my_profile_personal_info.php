<?php
include("../connect.php");
include("./session.php");

// Update form data if the form is submitted
if (isset($_POST['submit'])) {

    // Validate CSRF token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed.");
    }

    // Sanitize and validate input data
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);

    // Prepare update query
    $sql = "UPDATE users_data SET username = '{$username}', firstName = '{$firstName}', lastName = '{$lastName}', gender = '{$gender}', date_of_birth = '{$date_of_birth}'";

    // Check if profile image is uploaded
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
        // Validate and process uploaded file
        $file_name = $_FILES['profile_img']['name'];
        $file_tmp_name = $_FILES['profile_img']['tmp_name'];
        $file_size = $_FILES['profile_img']['size'];
        $file_type = $_FILES['profile_img']['type'];

        // Validate file type and size (example: allow only images less than 5MB)
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $max_file_size = 5 * 1024 * 1024; // 5MB
        if (in_array($file_type, $allowed_types) && $file_size <= $max_file_size) {
            // Move uploaded file to destination directory
            $destination = "../assets/images/" . $file_name;
            if (move_uploaded_file($file_tmp_name, $destination)) {
                // Add profile_pic field to the update query
                $sql .= ", profile_pic = '{$file_name}'";
            } else {
                echo "Failed to upload profile picture.";
            }
        } else {
            echo "Invalid file format or size. Allowed formats: JPEG, PNG, GIF (max size: 5MB).";
        }
    }

    // Add WHERE clause to the update query
    $sql .= " WHERE id = '{$users_data['id']}'";

    // Execute the update query
    if (mysqli_query($conn, $sql)) {
        header("Location: ./my_profile_personal_info.php");
        exit();
    } else {
        echo "Failed to update profile information.";
    }
}


// CSRF Protection
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

$header_data = ['css' => 'profile_personal', 'active' => 5];
include("./header.php");

?>

        <section class="banner">

        </section>

        <section class="profile-section">
            <div>
                <div class="profile-info">
                    <div class="img">
                        <img id = "previewImg" src="../assets/images/<?php echo $users_data['profile_pic'] ?? "p3.jpg" ?>" alt="">
                        <div class="name"><?= $users_data['firstName'] . " " . $users_data['lastName'] ?></div>
                        <div class="c_name"><?= $users_data['company_name'] ?></div>
                    </div>

                    <div class="info"><?= $users_data['email'] ?></div>
                    <div class="info"><?= $users_data['gender'] ?></div>
                </div> 

                <div class="profile-update-form">

                    <div class="tabs">
                        <div><a href="./my_profile.php">Home</a> </div>
                        <div class="active"><a href="./my_profile_personal_info.php">Perfonal Info</a> </div>
                        <div><a href="./my_profile_contact_info.php">Contact Info</a> </div>
                        <div><a href="./my_profile_privacy.php">Privacy</a> </div>
                    </div>


                    <div class="form">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?= $token ?>">

                            <div class="form-row">
                                <div>
                                    <label for="profile-img">Profile Image</label>
                                    <input type="file" name="profile_img" id="profile-img" accept="image/*">
                                </div>


                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="firstName">First Name</label>
                                    <input type="text" name="firstName" id="firstName" value="<?= $users_data['firstName'] ?? "" ?>">
                                </div>
                                <div class="form-col">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" name="lastName" id="lastName" value="<?= $users_data['lastName'] ?? "" ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-col">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" value="<?= $users_data['username'] ?? "" ?>">
                                </div>
                                <div class="form-col">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="dob" value="<?= $users_data['date_of_birth'] ?? "" ?>">
                                </div>
                            </div>
                            <div class="form-row">

                                <div class="form-col gender-col">
                                    <label for="gender">Gender</label>
                                    <div class="gender-cell">
                                        <input type="radio" name="gender" value="Male" id="male" <?= (($users_data['gender'] ?? "wrong") == "Male") ? "checked" : "" ?>><label for="male">Male</label>
                                        <input type="radio" name="gender" value="Female" id="female" <?= (($users_data['gender'] ?? "wrong") == "Female") ? "checked" : "" ?>><label for="female">Female</label>
                                    </div>
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

</body>

</html>