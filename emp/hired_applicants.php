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
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/application.css">
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/add/cand.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="applicants">
                <div class="body">
                    <div class="label">
                        <h4>ALL HIRED APPLICANTS</h4>
                    </div>
                    <div class="table" style="padding: 5px">
                        <table>
                            <thead>
                                <tr>
                                    <th style="min-width: 100px">Fullname</th>
                                    <th style="min-width: 150px">Job Position</th>
                                    <th style="width: 10%; min-width: 100px">Applied Date</th>
                                    <th style="width: 10%; min-width: 100px">View Details</th>
                                    <th>Status</th>
                                    <th>Hired Date</th>
                                    <th>Ratings</th>
                                    <th>Ranked</th>
                                </tr>
                            </thead>
                            <?php
                            $newApp = $conn->prepare("SELECT c.*, ja.*, a.*, j.* FROM candidates c JOIN job_applicants ja ON c.candidate_applied_id = ja.applied_id JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                            JOIN jobs j ON ja.applied_job_id = j.job_id WHERE c.candidate_status = 'Approved' ORDER BY ja.applied_ratings DESC");
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
                                            <td><?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?></td>
                                            <td style="text-transform:uppercase"><?php echo htmlspecialchars($new['job_position']) ?></td>
                                            <td><?php echo htmlspecialchars($new['applied_date']) ?></td>
                                            <td><a href="http://localhost/smarthr/emp/view_details.php?application=<?php echo htmlspecialchars($new['applied_id']) ?>">View Details</a></td>
                                            <td><?php echo htmlspecialchars($new['candidate_status']) ?></td>
                                            <td><?php echo htmlspecialchars($new['added_date']) ?></td>
                                            <td><?php echo htmlspecialchars($new['applied_ratings']) ?></td>
                                            <td><?php echo $newCount++ ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No Candidates Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
    <script>
        function showApprove(candidateId) {
            document.getElementById("approveModal" + candidateId).style.display = "flex";
        }

        function closeApproveModal(candidateId) {
            document.getElementById("approveModal" + candidateId).style.display = "none";
        }

        function showDecline(candidateId) {
            document.getElementById("declineModal" + candidateId).style.display = "flex";
        }

        function closeDeclineModal(candidateId) {
            document.getElementById("declineModal" + candidateId).style.display = "none";
        }
    </script>
</body>

</html>