<?php 
    include("../connect.php");
    include("./session.php");

    // Fetch jobs posted by the current employer
    $sql = "SELECT * FROM posted_jobs WHERE employer_id = '{$users_data['id']}'";
    $result = mysqli_query($conn, $sql) or die("Query Failed: " . mysqli_error($conn));
    
    // Include header file
    $header_data = ['id' => 3, 'title' => 'My Jobs Page'];
    require('./header.php');
?>





    <!-- ======================== Main section ====================== -->

    <main>
        <section class = "main-title">
            <!-- Dynamically changed -->
            Employer Dashboard
        </section>
        <section class = "table-section">
            <table>
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Job Location</th>
                        <th>Max CTC Budget</th>
                        <th>Applications</th>
                        <th>Action</th>
                    </tr>
                </thead>


                <!-- =================== dynamic database content 👇 -->
                <tbody>

                    <?php 
                    if(mysqli_num_rows($result) > 0){
                        while($data = mysqli_fetch_assoc($result)){

                            // count applications for each job posted.
                            $query = "SELECT count(job_id) as count_job FROM applied_jobs WHERE job_id = '{$data['id']}'";
                            $result2 = mysqli_query($conn, $query);
                            $count = mysqli_fetch_assoc($result2);
                    ?>

                    <tr>
                        <td><?php echo $data['job_title']; ?></td>
                        <td><?php echo $data['job_location']; ?></td>
                        <td><?php echo $data['budget_ctc']; ?></td>

                        <!-- fetch data count of application using job_id -->
                        <td>
                            <a href="./applied_candidates.php?job_id=<?php echo $data['id']; ?>">
                                <?php echo $count['count_job']; ?>
                            </a>
                        </td>
                        <td class = "action">
                            <a href="post_new_job.php?job_id=<?php echo $data['id']; ?>">Edit</a>
                            <a href="delete_job.php?job_id=<?php echo $data['id']; ?>">Delete</a>
                        </td>
                    </tr>

                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>

        </section>
    </main>
    <!-- ========================= End Main ========================= -->




<?php require("../footer.php"); ?>
