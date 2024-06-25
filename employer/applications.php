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
        <div class="tab-link active" data-filterstatus="">All</div>
        <div class="tab-link" data-filterstatus="0">Pending</div>
        <div class="tab-link" data-filterstatus="2">Viewed</div>
    </div>
    <div class="sorts">
        <div class="sort">
            <select name="sort-filter" id="sort-jobs">
                <option value="" selected>--- Select Job Role ---</option>
                <?php while ($data = mysqli_fetch_assoc($result_filter_jobs)) : ?>
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

            let obj = {
                page_no: pageId,
            }
            if (status != "" || status == "0") {
                obj = {
                    ...obj,
                    status: status,
                }
            }

            if (sortFilter != "") {
                obj = {
                    ...obj,
                    sortFilter: sortFilter,
                }
            }

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
            
        });


        $(document).on("change", "#sort-jobs", function() {
            let sortFilter = $(this).val();
            let status = $(".tab-link.active").data("filterstatus");

            let obj = {
                page_no: 0,
            }
            if (status != "" || status == "0") {
                obj = {
                    ...obj,
                    status: status,
                }
            }

            if (sortFilter != "") {
                obj = {
                    ...obj,
                    sortFilter: sortFilter,
                }
            }


            $.ajax({
                url: "applications_ajax.php",
                method: "POST",
                data: obj,
                success: function(data) {
                    $(".dynamic-result").html(data);
                }
            });

        });


        $(document).on("click", ".tab-link", function() {

            $(this).addClass("active");
            $(this).siblings().removeClass("active");

            let obj = {
                page_no: 0,
            }

            let sortFilter = $("#sort-jobs").val();
            if (sortFilter != "") {
                obj = {
                    ...obj,
                    sortFilter: sortFilter,
                }
            }

            let status = $(this).data("filterstatus");
            if (status != "" || status == "0") {
                obj = {
                    ...obj,
                    status: status,
                }
            }


            $.ajax({
                url: "applications_ajax.php",
                method: "POST",
                data: obj,
                success: function(data) {
                    $(".dynamic-result").html(data);
                }
            });

        });

    });
</script>
</body>

</html>