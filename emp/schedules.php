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
    <script>
        function confirmSched(event) {
            event.preventDefault();

            if (confirm("Are you sure to do this?")) {
                event.target.submit();
            }
        }

        function confirmCandate(event) {
            event.preventDefault();

            if (confirm("Are you sure to add this applicant to candidate?")) {
                event.target.submit();
            }
        }

        function confirmDelete(event) {
            event.preventDefault();

            if (confirm("Are you sure to delete this?")) {
                event.target.submit();
            }
        }
    </script>
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="applicants">
                <div class="body">
                    <div class="label">
                        <h4>ALL SCHEDULE FOR INTERVIEW</h4>
                    </div>
                    <div class="table" style="padding: 5px">
                        <?php
                        $newApp = $conn->prepare("
                        SELECT s.*, ja.*, a.*, j.*, c.candidate_status 
                        FROM schedules s 
                        JOIN job_applicants ja ON s.schedule_applied_id = ja.applied_id 
                        JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                        JOIN jobs j ON ja.applied_job_id = j.job_id 
                        LEFT JOIN candidates c ON c.candidate_applied_id = ja.applied_id 
                        ORDER BY s.schedule_date ASC, s.schedule_time DESC
                        ");
                        $newApp->execute();
                        $new_result = $newApp->get_result();

                        $newCount = 1;
                        $newlys = [];
                        while ($new_rows = $new_result->fetch_assoc()) {
                            $newlys[] = $new_rows;
                        }
                        ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fullname</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Job Position</th>
                                    <th style="width: 10%; min-width: 100px">View Details</th>
                                    <th>Schedule Date</th>
                                    <th>Schedule Time</th>
                                    <th style="width: 200px">Action</th>
                                </tr>
                                <tr id="filterSearch">
                                    <td></td>
                                    <td><input type="search" id="filter_fullname" placeholder="Search by Fullname"></td>
                                    <td><input type="search" id="filter_phone" placeholder="Search by Phone Number"></td>
                                    <td><input type="search" id="filter_address" placeholder="Search by Address"></td>
                                    <td><input type="search" id="filter_email" placeholder="Search by Email"></td>
                                    <td>
                                        <select name="filter_job" id="filter_job">
                                            <option value="" selected>Select</option>
                                            <?php
                                            $jobInfo = $conn->prepare("SELECT * FROM jobs");
                                            $jobInfo->execute();
                                            $jobInfo_result = $jobInfo->get_result();

                                            $jobs = [];
                                            while ($row = $jobInfo_result->fetch_assoc()) {
                                                $jobs[] = $row;
                                            }
                                            ?>
                                            <?php if (!empty($jobs)): ?>
                                                <?php foreach ($jobs as $job): ?>
                                                    <option value="<?php echo htmlspecialchars($job['job_position']) ?>"><?php echo htmlspecialchars($job['job_position']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td><input type="date" id="filter_date"></td>
                                    <td><input type="time" id="filter_time"></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($newlys)): ?>
                                    <?php foreach ($newlys as $new): ?>
                                        <tr>
                                            <td><?php echo $newCount++ ?></td>
                                            <td><?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?></td>
                                            <td><?php echo htmlspecialchars($new['phonenumber']) ?></td>
                                            <td><?php echo htmlspecialchars($new['address']) ?></td>
                                            <td><?php echo htmlspecialchars($new['email']) ?></td>
                                            <td style="text-transform:uppercase"><?php echo htmlspecialchars($new['job_position']) ?></td>
                                            <td><a href="view_details.php?application=<?php echo htmlspecialchars($new['applied_id']) ?>">View Details</a></td>
                                            <td><?php echo htmlspecialchars($new['schedule_date']) ?></td>
                                            <td><?php echo htmlspecialchars($new['schedule_time']) ?></td>
                                            <td>
                                                <div class="action-btn">
                                                    <?php if ($new['candidate_status'] !== 'Approved' && $new['candidate_status'] !== 'Declined'): ?>
                                                        <button style="background-color: blue" onclick="showSchedModal(<?php echo htmlspecialchars($new['schedule_id']) ?>)">RESCHEDULE</button>
                                                        <form action="handlers/applicant/add_candidate.php" method="POST" onsubmit="confirmCandate(event)">
                                                            <input type="hidden" name="candidate_applied_id" value="<?php echo htmlspecialchars($new['applied_id']) ?>">
                                                            <button style="background-color: darkblue">CANDIDATE</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <?php if ($_SESSION['role'] !== "Staff"): ?>
                                                        <form action="handlers/applicant/delete_schedule.php" method="POST" onsubmit="confirmDelete(event)">
                                                            <input type="hidden" name="delete_schedule_id" value="<?php echo htmlspecialchars($new['schedule_id']) ?>">
                                                            <input type="hidden" name="applied_id" value="<?php echo htmlspecialchars($new['applied_id']) ?>">
                                                            <button style="background-color: red" type="submit" id="deleteActBtn">DELETE</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="sched-modal" id="schedModal<?php echo htmlspecialchars($new['schedule_id']) ?>">
                                            <div class="sched-content">
                                                <div class="sched-header">
                                                    <h5>RE-SCHEDULE</h5>
                                                </div>
                                                <div class="sched-body">
                                                    <form action="handlers/applicant/reschedule.php" method="POST" onsubmit="confirmSched(event)">
                                                        <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($new['schedule_id']) ?>">
                                                        <div class="sched-input">
                                                            <p>Schedule Date</p>
                                                            <input type="date" name="schedule_date" id="" required>
                                                        </div>
                                                        <div class="sched-input">
                                                            <p>Schedule Date</p>
                                                            <input type="time" name="schedule_time" id="" required>
                                                        </div>
                                                        <div class="sched-input-btn">
                                                            <input type="submit" name="submit_sched" value="SCHEDULE" style="background-color: blue">
                                                            <button style="background-color: red" onclick="closeSchedModal(<?php echo htmlspecialchars($new['schedule_id']) ?>)" type="button">CANCEL</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10">No Scheduled Applicants</td>
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
        function showSchedModal(appliedId) {
            document.getElementById("schedModal" + appliedId).style.display = "flex";
        }

        function closeSchedModal(appliedId) {
            document.getElementById("schedModal" + appliedId).style.display = "none";
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const filterRow = document.getElementById("filterSearch");
            const table = document.querySelector("tbody");

            filterRow.addEventListener("input", filterTable);

            function filterTable() {
                const fullname = filterRow.children[1].querySelector("input[type='search']").value.toLowerCase();
                const phone = filterRow.children[2].querySelector("input[type='search']").value.toLowerCase();
                const email = filterRow.children[3].querySelector("input[type='search']").value.toLowerCase();
                const schedule = filterRow.children[4].querySelector("input[type='search']").value.toLowerCase();
                const jobPosition = filterRow.children[5].querySelector("select").value.toLowerCase();
                const appliedDate = filterRow.children[7].querySelector("input[type='date']").value.toLowerCase();
                const scheduleTime = filterRow.children[8].querySelector("input[type='time']").value.toLowerCase();

                Array.from(table.rows).forEach(row => {
                    const cells = row.children;

                    const matches = [
                        matchesText(cells[1].textContent.toLowerCase(), fullname),
                        matchesText(cells[2].textContent.toLowerCase(), phone),
                        matchesText(cells[3].textContent.toLowerCase(), email),
                        matchesText(cells[4].textContent.toLowerCase(), schedule),
                        matchesText(cells[5].textContent.toLowerCase(), jobPosition),
                        matchesText(cells[7].textContent.toLowerCase(), appliedDate),
                        matchesText(cells[8].textContent.toLowerCase(), scheduleTime),
                    ];

                    row.style.display = matches.every(Boolean) ? "" : "none";
                });
            }

            function matchesText(cellContent, filterValue) {
                if (filterValue === "") return true;
                return cellContent.startsWith(filterValue);
            }
        });
    </script>
</body>

</html>