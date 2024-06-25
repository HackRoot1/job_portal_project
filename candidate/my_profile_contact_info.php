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
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $contact_no = filter_var($_POST['c_no'], FILTER_SANITIZE_STRING);
    $location = filter_var($_POST['location'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit();
    }

    // Prepare and execute the update query
    $sql = "UPDATE users_data
                SET email = ?, contact_no = ?, current_location = ?, address = ?
                WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $email, $contact_no, $location, $address, $users_data['id']);
    if ($stmt->execute()) {
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

$header_data = ['css' => 'profile_contact', 'active' => 4];
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
                        <div><a href="./my_profile.php">Home</a> </div>
                        <div><a href="./my_profile_personal_info.php">Perfonal Info</a> </div>
                        <div class="active"><a href="./my_profile_contact_info.php">Contact Info</a> </div>
                        <div><a href="./my_profile_privacy.php">Privacy</a> </div>
                    </div>


                    <div class="form">
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

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
                                    <label for="email">E-mail</label>
                                    <input type="text" name="email" id="email" value="<?= $users_data['email'] ?? "" ?>">
                                </div>
                                <div class="form-col">
                                    <label for="c_no">Contact No</label>
                                    <input type="text" name="c_no" id="c_no" value="<?= $users_data['contact_no'] ?? "" ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-col">
                                    <label for="location">Current Location</label>
                                    <input type="text" name="location" id="location" value="<?= $users_data['current_location'] ?? "" ?>">
                                </div>
                                <div class="form-col">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" value="<?= $users_data['address'] ?? "" ?>">
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