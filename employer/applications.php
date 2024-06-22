<?php
include("../connect.php");
include("./session.php");

$header_data = ['css' => 'applications', 'active' => 4];
include("./header.php");
?>

        <section class="filters-section">
            <div class="tabs">
                <div class="tab-link active">All</div>
                <div class="tab-link">Pending</div>
                <div class="tab-link">Completed</div>
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
                        url: "pagination_my_jobs.php",
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