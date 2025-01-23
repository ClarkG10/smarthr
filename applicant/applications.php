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

    <style>
        .add-modal {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 5;
            background-color: rgba(0, 0, 0, 0.1);
            width: 100%;
            height: 100vh;
            display: none;
            justify-content: center;
            padding-top: 20px;
        }

        .add-modal .add-content {
            background-color: white;
            max-width: 350px;
            width: 100%;
            height: 150px;
            border-radius: 7px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .add-modal .add-content .add-header {
            padding: 10px;
            text-align: center;
        }

        .add-modal .add-content .add-body {
            padding: 0px 20px;
            display: flex;
            flex-direction: column;
        }

        .add-modal .add-content .add-body .add-select {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .add-modal .add-content .add-body .add-select select {
            width: 100%;
            height: 35px;
            outline: none;
        }

        .add-modal .add-content .add-body .add-select p {
            font-size: 12px;
        }

        .add-modal .add-content .add-body .add-button {
            display: flex;
            align-items: center;
            justify-content: end;
            gap: 5px;
        }

        .add-modal .add-content .add-body .add-button button {
            padding: 5px 7px;
            border: none;
            outline: none;
            color: white;
            font-weight: 600;
            border-radius: 5px;
        }

        .table table td i {
            color: red;
            margin-top: -10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="application">
                <div class="wrapper">
                    <div class="label">
                        <h4>ALL APPLICATIONS</h4>
                        <button onclick="showAddModal()">ADD APPLICATION</button>
                    </div>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <th>Job Position</th>
                                    <th>Work Place</th>
                                    <th style="width: 10%">Applied Date</th>
                                    <th style="width: 10%">Partial Rating</th>
                                    <th style="width: 10%">Final Rating</th>
                                    <th style="width: 10%">View Details</th>
                                    <th>Status</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <?php
                            $applied = $conn->prepare("SELECT ja.*, a.*, j.* FROM job_applicants ja JOIN applicants a ON ja.applied_applicant_id = a.applicant_id JOIN jobs j ON ja.applied_job_id = j.job_id WHERE ja.applied_applicant_id = $userId");
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
                                            <td><?php echo htmlspecialchars($application['partial_rating']) ?></td>
                                            <td><?php echo htmlspecialchars($application['applied_ratings']) ?></td>
                                            <td><a href="http://localhost/smarthr/applicant/view_details.php?application=<?php echo htmlspecialchars($application['applied_id']) ?>">View Details</a></td>
                                            <td><?php echo htmlspecialchars($application['sched_status']) ?> for Interview</td>
                                            <td>
                                                <div class="act-btn">
                                                    <?php if ($application['sched_status'] !== "Scheduled"): ?>
                                                        <form action="http://localhost/smarthr/applicant/handlers/cancel_apply.php" method="POST" onsubmit="confirmCancel(event)">
                                                            <input type="hidden" name="cancel_applied_id" value="<?php echo htmlspecialchars($application['applied_id']) ?>">
                                                            <button type="submit" name="cancelBtn">CANCEL</button>
                                                        </form>
                                                    <?php else: ?>
                                                        <p style="color: red; text-transform:uppercase">Can't Cancel</p>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8">No Appplication Found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="add-modal" id="addModal">
                    <div class="add-content">
                        <div class="add-header">
                            <h4>Available Job Opportunities</h4>
                        </div>
                        <div class="add-body">
                            <?php
                            $jobInfo = $conn->prepare("SELECT * FROM jobs");
                            $jobInfo->execute();
                            $jobInfo_result = $jobInfo->get_result();

                            $jobs = [];
                            while ($row = $jobInfo_result->fetch_assoc()) {
                                $jobs[] = $row;
                            }
                            ?>
                            <div class="add-select">
                                <select name="jobName" id="jobName">
                                    <option value="" selected disabled>Select Job</option>
                                    <?php if (!empty($jobs)): ?>
                                        <?php foreach ($jobs as $job): ?>
                                            <option value="<?php echo htmlspecialchars($job['job_id']) ?>"><?php echo htmlspecialchars($job['job_position']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No Available Jobs</option>
                                    <?php endif; ?>
                                </select>
                                <p>Select Job</p>
                            </div>
                            <div class="add-button">
                                <button onclick="closeAddModal()" style="background-color: red">CLOSE</button>
                                <button onclick="addApplication()" style="background-color: #2563EB">APPLY</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/applicant/js/navigation.js"></script>
    <script>
        function showAddModal() {
            document.getElementById("addModal").style.display = "flex";
        }

        function closeAddModal() {
            document.getElementById("addModal").style.display = "none";
        }

        function addApplication() {
            const jobSelect = document.getElementById("jobName");

            // Check if a valid job is selected (not empty and not zero)
            if (jobSelect.value !== "" && jobSelect.value !== "0") {
                // Confirm if the user really wants to apply
                if (confirm("Are you sure you want to apply for this job?")) {
                    window.location.href = "http://localhost/smarthr/applicant/apply.php?job_id=" + jobSelect.value;
                }
            } else {
                // If no valid job is selected, show an alert
                alert("Please select a job.");
            }
        }

        function confirmCancel(event) {
            event.preventDefault();

            if (confirm('Are you sure you want to cancel this application?')) {
                event.target.submit();
            }
        }
    </script>
</body>

</html>