<?php
include("../connect.php");
include("./session.php");

// Default SQL query to fetch all posted jobs
$sql = "SELECT * FROM posted_jobs";

// CSRF Protection
$token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $token;

$header_data = ['css' => 'search_jobs', 'active' => 2];
include("./header.php");

?>



        <section class="filters-section">

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" id = "search-job-form">

                <input type="hidden" name="csrf_token" id = "csrf_token" value="<?= $token ?>">

                <div>
                    <label for="job-type">Job Type:</label>
                    <select name="job_type" id="job-type">
                        <option value="">--- Select ---</option>
                        <option value="full-time">Full Time</option>
                        <option value="part-time">Part Time</option>
                        <option value="contract">Contract Based</option>
                    </select>
                </div>

                <div>
                    <label for="job_location">Job Location:</label>
                    <select name="job_location" id="job_location">
                        <option value="">--- Select ---</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="delhi">Delhi</option>
                        <option value="bangalore">Bangalore</option>
                    </select>
                </div>

                <div>
                    <input type="submit" name="search" value="Search">
                </div>
            </form>
        </section>


        <!-- load dynamic data here  -->
        <div class="dynamic-result"></div>

    </main>


    <script>
        $(document).ready(function() {

            function loadData() {
                $.ajax({
                    url: "search_jobs_ajax.php",
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
                        url: "search_jobs_ajax.php",
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
            
            $(document).on("submit", "#search-job-form", function(e) {
                e.preventDefault();
                // let formData = $(this).serialize();
                let csrf_token = $("#csrf_token").val();
                let job_type = $("#job-type").val() || "";
                let job_location = $("#job_location").val() || "";
                // alert(job_location || "");

                $.ajax({
                    url: "search_jobs_ajax.php",
                    method: "POST",
                    data: {
                        page_no : 0,
                        csrf_token : csrf_token,
                        job_type : job_type,
                        job_location : job_location,
                    },
                    success: function(data) {
                        $(".dynamic-result").html(data);
                    }
                });

            });
        });
    </script>
</body>

</html>