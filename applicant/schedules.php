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
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/applications.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="application">
                <div class="wrapper">
                    <div class="label">
                        <h4>APPLICATION SCHEDULE</h4>
                    </div>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <th>Job Position</th>
                                    <th>Work Place</th>
                                    <th style="width: 10%">Applied Date</th>
                                    <th style="width: 10%">Ratings</th>
                                    <th style="width: 10%">View Details</th>
                                    <th style="width: 10%">Schedule Date</th>
                                    <th style="width: 10%">Schedule Time</th>
                                </tr>
                            </thead>
                            <?php
                            $applied = $conn->prepare("SELECT s.*, ja.*, a.*, j.* FROM schedules s JOIN job_applicants ja ON s.schedule_applied_id = ja.applied_id JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                                    JOIN jobs j ON ja.applied_job_id = j.job_id WHERE s.schedule_applied_id = ja.applied_id AND a.applicant_id = $userId ORDER BY s.schedule_date ASC, s.schedule_time DESC");
                            $applied->execute();
                            $applied_result = $applied->get_result();

                            $appliCount = 1;
                            $applications = [];
                            while ($applied_rows = $applied_result->fetch_assoc()) {
                                $applications[] = $applied_rows;
                            }
                            ?>
                            <tbody>
                                <?php if (!empty($applications)): ?>
                                    <?php foreach ($applications as $application): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($appliCount++) ?></td>
                                            <td><?php echo htmlspecialchars($application['job_position']) ?></td>
                                            <td><?php echo htmlspecialchars($application['place']) ?></td>
                                            <td><?php echo htmlspecialchars($application['applied_date']) ?></td>
                                            <td><?php echo htmlspecialchars($application['applied_ratings']) ?></td>
                                            <td><a href="http://localhost/smarthr/applicant/view_details.php?application=<?php echo htmlspecialchars($application['applied_id'])?>">View Details</a></td>
                                            <td><?php echo htmlspecialchars($application['schedule_date']) ?></td>
                                            <td><?php echo htmlspecialchars($application['schedule_time']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No Schedule Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/applicant/js/navigation.js"></script>
</body>

</html>