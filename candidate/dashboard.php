<?php 
    include("../connect.php");
    include("./session.php");

    $header_data = ['id' => 1, 'title' => 'Candidate Dashboard'];
    include("./main_header.php");

?>


    <!-- ======================== Main section ====================== -->

    <main>
        <section class = "main-title">
            <!-- Dynamically changed -->
            Dashboard
        </section>
        <section class = "main-section">
            <div style = "font-size: 30px;">
                Welcome to Job Portal Candidate Dashboard
            </div>
        </section>
    </main>
    <!-- ========================= End Main ========================= -->



<?php require("../footer.php"); ?>