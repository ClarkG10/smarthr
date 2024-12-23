<?php
require "../database/connection.php";
require "handlers/authenticate.php";
require "handlers/user_logged.php";
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
    <!-- APPLICANT CSS CONTENTS -->
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/navigations.css">
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/opportunities.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="opportunities">
                <?php
                $openJobs = $conn->prepare("SELECT * FROM jobs WHERE status = 'Open'");
                $openJobs->execute();
                $openResult = $openJobs->get_result();

                $jobs = [];
                while ($openRow = $openResult->fetch_assoc()) {
                    $jobs[] = $openRow;
                }
                ?>
                <div class="wrapper">
                    <?php if (!empty($jobs)): ?>
                        <?php foreach ($jobs as $job): ?>
                            <div class="job-wrap">
                                <div class="info">
                                    <h3><?php echo htmlspecialchars($job['job_position']) ?></h3>
                                    <p><strong>Open Position : </strong><?php echo htmlspecialchars($job['open_position']) ?></p>
                                    <p><strong>Work Place : </strong><?php echo htmlspecialchars($job['place']) ?></p>
                                </div>
                                <button onclick="showViewModal(<?php echo htmlspecialchars($job['job_id']) ?>)">View Details<i class="fa-solid fa-arrow-right"></i></button>
                            </div>
                            <?php include "includes/contents/view-job.php" ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No Jobs Opportunities</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/applicant/js/navigation.js"></script>
    <script>
        function showViewModal(jobId) {
            document.getElementById("viewModal" + jobId).style.display = "flex";
        }

        function closeViewModal(jobId) {
            document.getElementById("viewModal" + jobId).style.display = "none";
        }
    </script>
</body>

</html>