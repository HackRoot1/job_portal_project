<?php 
    include("../connect.php");
    include("./session.php");

    $header_data = ['id' => 1, 'title' => 'Employee Dashboard'];
    require('./header.php');

?>

    <!-- ======================== Main section ====================== -->

    <main>
        <section class = "main-title">
            <!-- Dynamically changed -->
            Employer Dashboard
        </section>
        <section class = "main-section">
            <div style = "font-size: 30px;">
                Welcome <?php echo $_SESSION['username']; ?> to Job Portal Employer Dashboard
            </div>
        </section>
    </main>
    <!-- ========================= End Main ========================= -->

    <?php require("../footer.php"); ?>