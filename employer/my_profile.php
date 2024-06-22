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
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $company_address = mysqli_real_escape_string($conn, $_POST['company_address']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $date_of_birth = $_POST['date_of_birth']; // No need to escape as it's a date field

    // Validate date of birth format
    if (!strtotime($date_of_birth)) {
        echo "Invalid date of birth format. Please enter in YYYY-MM-DD format.";
        exit();
    }

    // Prepare and execute the update query
    $sql = "UPDATE 
                users_data
            SET 
                firstName = ?, 
                lastName = ?, 
                company_name = ?, 
                company_address = ?, 
                gender = ?, 
                date_of_birth = ?
            WHERE 
                id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $firstName, $lastName, $company_name, $company_address, $gender, $date_of_birth, $users_data['id']);
    if ($stmt->execute()) {
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

$header_data = ['css' => 'profile', 'active' => 5];
include("./header.php");


?>

        <section class="banner">

        </section>

        <section class="profile-section">
            <div>
                <div class="profile-info">
                    <div class="img">
                        <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "p3.jpg" ?>" alt="">
                        <div class="name"><?= $users_data['firstName'] . " " . $users_data['lastName'] ?></div>
                        <div class="c_name"><?= $users_data['company_name'] ?></div>
                    </div>

                    <div class="info"><?= $users_data['email'] ?></div>
                    <div class="info"><?= $users_data['gender'] ?></div>
                </div>

                <div class="profile-update-form">

                    <div class="tabs">
                        <div class="active"><a href="./my_profile.php">Home</a> </div>
                        <div><a href="./my_profile_personal_info.php">Perfonal Info</a> </div>
                        <div><a href="./my_profile_contact_info.php">Contact Info</a> </div>
                        <div><a href="./my_profile_privacy.php">Privacy</a> </div>
                    </div>

                    <div class="form">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">

                            <!-- CSRF Token -->
                            <input type="hidden" name="csrf_token" value="<?= $token ?>">
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
                                    <label for="company_name">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" value="<?= $users_data['company_name'] ?? "" ?>">
                                </div>
                                <div class="form-col">
                                    <label for="company_address">Company Address</label>
                                    <input type="text" name="company_address" id="company_address" value="<?= $users_data['company_address'] ?? "" ?>">
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
                                <div class="form-col">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="dob" value="<?= $users_data['date_of_birth'] ?? "" ?>">
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