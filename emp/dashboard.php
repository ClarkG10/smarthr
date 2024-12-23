<?php
session_start();
require "../database/connection.php";
require "handlers/authenticate.php";
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
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/dashboard.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="dashboard">
                <?php require "handlers/count_all.php" ?>
                <div class="header">
                    <div class="item">
                        <p>Total of Applicants</p>
                        <span><?php echo $total_applied ?></span>
                    </div>
                    <div class="item">
                        <p>Total of Candidates</p>
                        <span><?php echo $total_candidate ?></span>
                    </div>
                    <div class="item">
                        <p>Total of Jobs</p>
                        <span><?php echo $total_job ?></span>
                    </div>
                    <?php if ($_SESSION['role'] !== "Staff"): ?>
                        <div class="item">
                            <p>Total of System Users</p>
                            <span><?php echo $total_users ?></span>
                        </div>
                    <?php else: ?>
                        <div class="item">
                            <p>Total Open Jobs</p>
                            <span><?php echo $total_job_open ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="body">
                    <div class="item">
                        <div class="label">
                            <p>NEWLY APPLICANTS</p>
                            <a href="http://localhost/smarthr/emp/application.php">View All</a>
                        </div>
                        <div class="table">
                            <table>
                                <thead>
                                    <tr>
                                        <th style="width: 30px">#</th>
                                        <th style="min-width: 100px">Fullname</th>
                                        <th style="min-width: 100px">Phone Number</th>
                                        <th style="min-width: 100px">Email</th>
                                        <th style="min-width: 150px">Job Position</th>
                                        <th style="width: 10%; min-width: 100px">Applied Date</th>
                                        <th style="width: 10%; min-width: 100px">View Details</th>
                                    </tr>
                                </thead>
                                <?php
                                $newApp = $conn->prepare("SELECT ja.*, a.*, j.* FROM job_applicants ja JOIN applicants a ON ja.applied_applicant_id = a.applicant_id JOIN jobs j ON ja.applied_job_id = j.job_id ORDER BY ja.applied_date DESC");
                                $newApp->execute();
                                $new_result = $newApp->get_result();

                                $newCount = 1;
                                $newlys = [];
                                while ($new_rows = $new_result->fetch_assoc()) {
                                    $newlys[] = $new_rows;
                                }
                                ?>
                                <tbody>
                                    <?php if (!empty($newlys)): ?>
                                        <?php foreach ($newlys as $new): ?>
                                            <tr>
                                                <td><?php echo $newCount++ ?></td>
                                                <td><?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?></td>
                                                <td><?php echo htmlspecialchars($new['phonenumber']) ?></td>
                                                <td><?php echo htmlspecialchars($new['email']) ?></td>
                                                <td style="text-transform:uppercase"><?php echo htmlspecialchars($new['job_position']) ?></td>
                                                <td><?php echo htmlspecialchars($new['applied_date']) ?></td>
                                                <td><a href="http://localhost/smarthr/emp/view_details.php?application=<?php echo htmlspecialchars($new['applied_id'])?>">View Details</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8">No New Applicants Found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="item">
                        <?php
                        $currentDay = date('Y-m-d');
                        $nextDay = date('Y-m-d', strtotime('+2 day'));
                        ?>
                        <div class="label">
                            <p>UPCOMING SCHEDULE INTERVIEW</p>
                            <a href="http://localhost/smarthr/emp/schedules.php">View All</a>
                        </div>
                        <div class="table">
                            <table>
                                <?php
                                $fetch_is = $conn->prepare("SELECT s.*, a.firstname, a.lastname FROM schedules s JOIN job_applicants ja ON s.schedule_applied_id = ja.applied_id 
                                        JOIN applicants a ON ja.applied_applicant_id = a.applicant_id WHERE s.schedule_date BETWEEN ? AND ? ORDER BY s.schedule_date ASC, s.schedule_time ASC");
                                $fetch_is->bind_param("ss", $currentDay, $nextDay);
                                $fetch_is->execute();
                                $result = $fetch_is->get_result();

                                $countIs = 1;
                                $currents = [];
                                while ($fetch_row = $result->fetch_assoc()) {
                                    $currents[] = $fetch_row;
                                }
                                ?>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="min-width: 150px">Fullname</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($currents as $current): ?>
                                        <tr>
                                            <td><?php echo $countIs++ ?></td>
                                            <td><?php echo htmlspecialchars($current['firstname'] . " " . $current['lastname']) ?></td>
                                            <td><?php echo htmlspecialchars($current['schedule_date']) ?></td>
                                            <td><?php echo htmlspecialchars($current['schedule_time']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
</body>

</html>