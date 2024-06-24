<?php
include("../connect.php");
include("./session.php");

$header_data = ['css' => 'applications', 'active' => 4];
include("./header.php");

$filter_jobs = "SELECT `id`, `job_title` FROM `posted_jobs` WHERE employer_id = {$users_data['id']}";
$result_filter_jobs = mysqli_query($conn, $filter_jobs);

?>

        <section class="filters-section">
            <div class="tabs">
                <div class="tab-link active">All</div>
                <div class="tab-link">Pending</div>
                <div class="tab-link">Completed</div>
            </div>
            <div class="sorts">
                <div class="sort">
                    <select name="sort-filter" id="sort-jobs">
                        <option value="" disabled selected>--- Select Job Role ---</option>
                        <?php while($data = mysqli_fetch_assoc($result_filter_jobs)): ?>
                            <option value="<?= $data['id'] ?>"><?= $data['job_title'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
        </section>


        <!-- load dynamic data here  -->
        <div class="dynamic-result"></div>


    </main>


    <script>
        $(document).ready(function() {

            function loadData() {
                $.ajax({
                    url: "applications_ajax.php",
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
                        url: "applications_ajax.php",
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
            
            $(document).on("change", "#sort-jobs", function() {
                let sortFilter = $(this).val();
                $.ajax({
                    url: "applications_ajax.php",
                    method: "POST",
                    data: {
                        sortFilter : sortFilter,
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