<?php
session_start();
require "../database/connection.php";

if (!isset($_SESSION['user_id']) && (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Staff' && $_SESSION['role'] !== 'Admin'))) {
    header("Location: http://localhost/smarthr/");
    exit();
}

require "handlers/logged_info.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BWD | APPLICANT</title>
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/global.css">
    <!-- EMP CSS CONTENTS -->
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/navigations.css">
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/manage.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="manage">
                <div class="wrap">
                    <div class="label">
                        <div class="div">
                            <a href="http://localhost/smarthr/emp/reports.php">Back</a>
                            <h4>ALL JOBS</h4>
                        </div>
                        <button onclick="showAddJobs()"><i class="fa-solid fa-plus"></i>ADD JOB</button>
                    </div>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Job Position</th>
                                    <th>Plantilla Item</th>
                                    <th>Pay Grade</th>
                                    <th>Monthly Salary</th>
                                    <th>Education</th>
                                    <th>Training</th>
                                    <th>Experience</th>
                                    <th>Eligibility</th>
                                    <th>Competency</th>
                                    <th>Place</th>
                                    <th>Open Position</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            $fetch_jobs = $conn->prepare("SELECT * FROM jobs");
                            $fetch_jobs->execute();
                            $fetch_result = $fetch_jobs->get_result();

                            $countJobs = 1;
                            $jobs = [];
                            while ($fetch_row = $fetch_result->fetch_assoc()) {
                                $jobs[] = $fetch_row;
                            }
                            ?>
                            <tbody>
                                <?php if (!empty($jobs)): ?>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td><?php echo $countJobs++ ?></td>
                                            <td><?php echo htmlspecialchars($job['job_position']) ?></td>
                                            <td><?php echo htmlspecialchars($job['plantilla_item']) ?></td>
                                            <td><?php echo htmlspecialchars($job['pay_grade']) ?></td>
                                            <td><?php echo htmlspecialchars($job['monthly_salary']) ?></td>
                                            <td><?php echo htmlspecialchars($job['education']) ?></td>
                                            <td><?php echo htmlspecialchars($job['training']) ?></td>
                                            <td><?php echo htmlspecialchars($job['experience']) ?></td>
                                            <td><?php echo htmlspecialchars($job['eligibility']) ?></td>
                                            <td><?php echo htmlspecialchars($job['competency']) ?></td>
                                            <td><?php echo htmlspecialchars($job['place']) ?></td>
                                            <td><?php echo htmlspecialchars($job['open_position']) ?></td>
                                            <td><?php echo htmlspecialchars($job['status']) ?></td>
                                            <td>
                                                <div class="job-buttons">
                                                    <button style="background-color: blue" onclick="showUpdateModal(<?php echo htmlspecialchars($job['job_id']) ?>)">UPDATE</button>
                                                    <?php if ($_SESSION['role'] !== "Staff"): ?>
                                                        <form action="handlers/jobs/delete_job.php" method="POST" onsubmit="deleteConfirm(event)">
                                                            <input type="hidden" name="delete_job_id" value="<?php echo htmlspecialchars($job['job_id']) ?>">
                                                            <button style="background-color: red">DELETE</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php include "includes/contents/update_jobs.php" ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td>NO AVAILABLE JOBS</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php include "includes/contents/add_jobs.php" ?>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
    <script>
        function showAddJobs() {
            document.getElementById("addJobModal").style.display = "flex";
        }

        function closeAddJobModal() {
            document.getElementById("addJobModal").style.display = "none";
        }

        function showUpdateModal(jobId) {
            document.getElementById("updateJob" + jobId).style.display = "flex";
        }

        function closeUpdateModal(jobId) {
            document.getElementById("updateJob" + jobId).style.display = "none";
        }

        function updateConfirm(event) {
            event.preventDefault();

            if (confirm("Are you sure to update this?")) {
                event.target.submit();
            }
        }

        function deleteConfirm(event) {
            event.preventDefault();

            if (confirm("Are you sure to delete this?")) {
                event.target.submit();
            }
        }
    </script>
</body>

</html>