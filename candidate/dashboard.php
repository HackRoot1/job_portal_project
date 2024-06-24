<?php
include("../connect.php");
include("./session.php");

$header_data = ['css' => 'dashboard', 'active' => 1];
include("./header.php");

?>

        <section class="welcome">
            <div>
                Welcome <?php echo $_SESSION['username']; ?> To Job Portal Employer Dashboard
            </div>
        </section>

    </main>
</body>

</html>