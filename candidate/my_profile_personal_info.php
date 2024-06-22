<?php 
    include("../connect.php");
    include("./session.php");

    // Update form data if the form is submitted
    if(isset($_POST['submit'])) {

        // Validate CSRF token
        if(!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])){
            die("CSRF token validation failed.");
        }

        // Sanitize and validate input data
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $date_of_birth = mysqli_real_escape_string($conn, $_POST['date_of_birth']);

        // Prepare update query
        $sql = "UPDATE users_data SET username = ?, firstName = ?, lastName = ?, gender = ?, date_of_birth = ?";

        // Check if profile image is uploaded
        if(isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] === UPLOAD_ERR_OK) {
            // Validate and process uploaded file
            $file_name = $_FILES['profile_img']['name'];
            $file_tmp_name = $_FILES['profile_img']['tmp_name'];
            $file_size = $_FILES['profile_img']['size'];
            $file_type = $_FILES['profile_img']['type'];

            // Validate file type and size (example: allow only images less than 5MB)
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_file_size = 5 * 1024 * 1024; // 5MB
            if(in_array($file_type, $allowed_types) && $file_size <= $max_file_size) {
                // Move uploaded file to destination directory
                $destination = "../assets/images/" . $file_name;
                if(move_uploaded_file($file_tmp_name, $destination)) {
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
        if(mysqli_query($conn, $sql)) {
            header("Location: ./my_profile_personal_info.php");
            exit();
        } else {
            echo "Failed to update profile information.";
        }
    }

    
     // CSRF Protection
     $token = bin2hex(random_bytes(32));
     $_SESSION['csrf_token'] = $token;

?>


<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: row;
        }

        a {
            text-decoration: none;
            color: #fff;
        }

        .sidebar {
            background-color: #5d28d7;
            width: 250px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
        }

        .sidebar .menu {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .sidebar .menu .title {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
            font-size: 25px;
        }

        .sidebar .menu .tabs {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .sidebar .menu .tab-pills {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 30px;
            height: 40px;
            width: 80%;
            color: #fff;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
        }

        .sidebar .menu .tab-pills:hover {
            color: #000;
            background-color: aliceblue;
            cursor: pointer;
        }

        .sidebar .menu .tab-pills:hover a {
            color: #000;
        }

        .sidebar .settings {
            display: flex;
            flex-direction: column;
            margin-bottom: 10px;
            align-items: center;
            justify-content: center;
        }

        .sidebar .settings>div {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 80%;
            color: #fff;
            font-size: 18px;
            border-radius: 50px;
            cursor: pointer;
        }

        .sidebar .settings>div:hover {
            color: #000;
            background-color: aliceblue;
            cursor: pointer;
        }

        .sidebar .settings>div input {
            background-color: transparent;
            border: none;
            outline: none;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-size: 18px;
            font-family: serif;
            color: #fff;
            cursor: pointer;
        }

        
        .sidebar .settings>div:hover a{
            color: #000;
        }
        .sidebar .settings>div:hover input{
            color: #000;
        }



        /* navbar design */
        nav {
            width: calc(100vw - 250px);
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            height: 70px;
            background-color: #f0f0f0;
            /* position: fixed; */
        }

        nav .title {
            font-size: 28px;
            font-weight: bold;
        }

        nav .links {
            height: 100%;
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        nav .links .search input {
            height: 40px;
            border-radius: 5px;
            outline: none;
            border: none;
            width: 250px;
            padding: 0 20px;
            font-size: 16px;
        }

        nav .links .profile {
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            background-color: #5d28d7;
            padding: 0px 20px;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }

        nav .links .profile img {
            width: 30px;
            height: 30px;
            border-radius: 50px;
        }

        /* end */


        .banner {
            background: url(../assets/images/cool-background.png);
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
            height: 200px;
            width: 100%;
        }

        .profile-section {
            padding: 0 30px;
        }

        .profile-section>div {
            padding: 0 30px;
            height: 300px;
            margin-top: -90px;
            display: flex;
            gap: 20px;
        }


        /* profile card */

        .profile-section .profile-info {
            background-color: #f0f0f0;
            border: 1px solid #000;
            height: 400px;
            width: 300px;
        }

        .profile-section .profile-info>div {
            display: flex;
            justify-content: center;
            align-items: center;
            border: 1px solid;
        }

        .profile-section .profile-info .img {
            width: 100%;
            height: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }

        .profile-section .profile-info .name {
            font-size: 25px;
            font-weight: bold;
        }

        .profile-section .profile-info .c_name {
            font-size: 16px;
        }

        .profile-section .profile-info .img img {
            height: 100px;
            width: 100px;
            border-radius: 50%;
        }

        .profile-section .profile-info .info {
            font-size: 18px;
            height: 35px;
        }

        /* end */


        /* form */
        .profile-update-form {
            background-color: #f0f0f0;
            border: 1px solid #000;
            min-height: 480px;
            width: 100%;
        }

        .profile-update-form .tabs {
            height: 70px;
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 0 20px;
            font-size: 18px;
        }
        .profile-update-form .tabs a{
            color: #000;
        }

        .profile-update-form .tabs>div {
            cursor: pointer;
        }

        .profile-update-form .tabs>div.active {
            border-bottom: 3px solid #5d28d7;
            font-weight: bold;
            padding: 10px 0;

        }

        .profile-update-form .form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-update-form .form form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .profile-update-form .form form .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            gap: 20px;
        }

        .profile-update-form .form form .form-row .form-col {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 50%;
        }
        .profile-update-form .form form .form-row .form-col.gender-col{
            display: flex;
            flex-direction: row;
            justify-content: start;
            align-items: center;
        }
        .profile-update-form .form form .form-row .form-col .gender-cell {
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .profile-update-form .form form .form-row .form-col input {
            height: 40px;
            border: none;
            outline: none;
            padding: 0 15px;
            border-radius: 5px;
        }

        .profile-update-form .form form .form-row input[type='submit'] {
            height: 40px;
            border: none;
            outline: none;
            padding: 0 15px;
            border-radius: 5px;
            background-color: #5d28d7;
            color: #fff;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
        }

        /* end */
    </style>
</head>

<body>
    <!-- ======================== Main section ====================== -->
    <section class="sidebar">
        <div class="menu">
            <div class="title">Something</div>
            <div class="tabs">
                <div class="tab-pills"><a href="./dashboard.php">Dashboard</a></div>
                <div class="tab-pills"><a href="./search_jobs.php">Search Jobs</a></div>
                <div class="tab-pills"><a href="./my_applied_jobs.php">My Applied Jobs</a></div>
            </div>
        </div>

        <div class="settings">
            <div><a href="./my_profile.php">Settings</a></div>
            <div>
                <form method="post">
                    <input type="submit" name="logout" value="Logout">
                </form>
            </div>
        </div>
    </section>


    <main>
        <nav>
            <div class="title">Dashboard</div>

            <div class="links">
                <div class="search">
                    <input type="search" name="" id="" />
                </div>
                <div class="profile">
                    <span>Profile</span>
                    <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "" ?>" alt="" />
                </div>
            </div>
        </nav>

        <section class="banner">

        </section>

        <section class="profile-section">
            <div>
                <div class="profile-info">
                    <div class="img">
                        <img src="../assets/images/<?php echo $users_data['profile_pic'] ?? "" ?>" id="previewImg" alt="">
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
                                    <input type="text" name = "username" id="username" value="<?= $users_data['username'] ?? "" ?>">
                                </div>
                                <div class="form-col">
                                    <label for="dob">Date of Birth</label>
                                    <input type="date" name="date_of_birth" id="dob" value="<?= $users_data['date_of_birth'] ?? "" ?>">
                                </div>
                            </div>
                            <div class = "form-row">
                                
                                <div class="form-col gender-col">
                                    <label for="gender">Gender</label>
                                    <div class = "gender-cell">
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
