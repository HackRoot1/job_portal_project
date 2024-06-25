<?php
include("../connect.php");
include("./session.php");

// Get the user's ID from the session
// $user_id = $users_data['id'] ?? null;

// Verify that the user is logged in
// if (!$user_id) {
//     // Redirect to the login page if the user is not logged in
//     header("Location: ./login.php");
//     exit();
// }

// // Retrieve user data from the database
// $stmt = $conn->prepare("SELECT * FROM users_data WHERE id = ?");
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();
// $data = $result->fetch_assoc();
// $stmt->close();

// // Retrieve applied jobs for the user
// $stmt2 = $conn->prepare("SELECT * FROM applied_jobs WHERE candidate_id = ? ORDER BY applied_time DESC");
// $stmt2->bind_param("i", $user_id);
// $stmt2->execute();
// $result2 = $stmt2->get_result();

$header_data = ['css' => 'my_applied_jobs', 'active' => 3];
include("./header.php");

?>

        <section class="filters-section">
            <div class="tabs">
                <div class="tab-link active">All</div>
                <div class="tab-link">Pending</div>
                <div class="tab-link">Cancelled</div>
            </div>
            <div class="sorts">
                <div class="sort">31-01-2000</div>
                <div>To</div>
                <div class="sort">31-01-2000</div>
            </div>
        </section>


        <!-- load dynamic data here  -->
        <div class="dynamic-result"></div>



    </main>

    <script>
        $(document).ready(function() {

            function loadData() {
                $.ajax({
                    url: "applied_jobs_ajax.php",
                    method: "POST",
                    data: {
                        page_no: 0
                    },
                    success: function(data) {
                        $(".dynamic-result").html(data);
                    }
                });
            }

            loadData();

            $(document).on("click", ".page", function() {
                let pageId = $(this).data("pageid");
                // alert(pageId);
                if (pageId >= 0) {

                    $.ajax({
                        url: "applied_jobs_ajax.php",
                        method: "POST",
                        data: {
                            page_no: pageId
                        },
                        success: function(data) {
                            $(".dynamic-result").html(data);
                        }
                    });
                }

            });
            
            
        });
    </script>
</body>

</html>