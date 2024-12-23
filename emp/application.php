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
                        <h4>ALL APPLICANTS APPLICATION</h4>
                    </div>
                    <div class="table" style="padding:5px">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width: 30px">#</th>
                                    <th style="min-width: 100px">Fullname</th>
                                    <th style="min-width: 100px">Phone Number</th>
                                    <th style="min-width: 100px">Email</th>
                                    <th style="min-width: 150px">Job Position</th>
                                    <th style="width: 10%; min-width: 100px">Applied Date</th>
                                    <th>Status</th>
                                    <th>Ratings</th>
                                    <th>Program</th>
                                    <th style="width: 10%; min-width: 100px">View Details</th>
                                    <th>Action</th>
                                </tr>
                                <tr id="filterSearch">
                                    <td></td>
                                    <td><input type="search" placeholder="Search by Fullname"></td>
                                    <td><input type="search" placeholder="Search by Phone Number"></td>
                                    <td><input type="search" placeholder="Search by Email"></td>
                                    <td>
                                        <select name="filter_job" id="filter_job">
                                            <?php
                                            $jobInfo = $conn->prepare("SELECT * FROM jobs");
                                            $jobInfo->execute();
                                            $jobInfo_result = $jobInfo->get_result();

                                            $jobs = [];
                                            while ($row = $jobInfo_result->fetch_assoc()) {
                                                $jobs[] = $row;
                                            }
                                            ?>
                                            <option value="" selected>Select</option>
                                            <?php if (!empty($jobs)): ?>
                                                <?php foreach ($jobs as $job): ?>
                                                    <option value="<?php echo htmlspecialchars($job['job_position']) ?>"><?php echo htmlspecialchars($job['job_position']) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                    <td><input type="date" placeholder="Search by Date"></td>
                                    <td>
                                        <select>
                                            <option value="" selected>Select</option>
                                            <option value="Scheduled">Scheduled</option>
                                            <option value="No Schedule">No Schedule</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td>
                                        <select name="filter_program" id="filter_program">
                                            <?php
                                            $jobInfo = $conn->prepare("SELECT * FROM job_applicants");
                                            $jobInfo->execute();
                                            $jobInfo_result = $jobInfo->get_result();

                                            $programs = [];

                                            while ($row = $jobInfo_result->fetch_assoc()) {
                                                $program = trim($row['applied_program']);
                                                if (!empty($program)) {
                                                    $programs[] = $program;
                                                }
                                            }

                                            // Remove duplicates from the array
                                            $programs = array_unique($programs);
                                            ?>
                                            <option value="" selected>Select Program</option>
                                            <?php if (!empty($programs)): ?>
                                                <?php foreach ($programs as $program): ?>
                                                    <option value="<?php echo htmlspecialchars($program) ?>"><?php echo htmlspecialchars($program) ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td></td>
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
                                            <td><?php echo htmlspecialchars($new['sched_status']) ?></td>
                                            <td><?php echo htmlspecialchars($new['applied_ratings']) ?></td>
                                            <td><?php echo htmlspecialchars($new['applied_program']) ?></td>
                                            <td><a href="view_details.php?application=<?php echo htmlspecialchars($new['applied_id']) ?>">View Details</a></td>
                                            <td>
                                                <div class="action-btn">
                                                    <?php if (($new['sched_status'] !== 'Declined') && ($new['sched_status'] !== 'Scheduled')): ?>
                                                        <button style="background-color: blue" onclick="showSchedModal(<?php echo htmlspecialchars($new['applied_id']) ?>)">SCHEDULE</button>
                                                        <button style="background-color: orangered" onclick="showDecline(<?php echo htmlspecialchars($new['applied_id']) ?>)">DECLINE</button>
                                                    <?php endif; ?>
                                                    <?php if ($_SESSION['role'] !== 'Staff'): ?>
                                                        <form action="handlers/applicant/delete_applicant.php" method="POST" onsubmit="deleteConfirm(event)">
                                                            <input type="hidden" name="delete_applied_id" value="<?php echo htmlspecialchars($new['applied_id']) ?>">
                                                            <button style="background-color: red" type="submit" id="deleteActBtn">DELETE</button>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="sched-modal" id="schedModal<?php echo htmlspecialchars($new['applied_id']) ?>">
                                            <div class="sched-content">
                                                <div class="sched-header">
                                                    <h5>ADD SCHEDULE</h5>
                                                </div>
                                                <div class="sched-body">
                                                    <form action="handlers/applicant/add_sched.php" method="POST" onsubmit="confirmSched(event)">
                                                        <input type="hidden" name="sched_applied_id" value="<?php echo htmlspecialchars($new['applied_id']) ?>">
                                                        <div class="sched-input">
                                                            <p>Schedule Date</p>
                                                            <input type="date" name="sched_date" id="" required>
                                                        </div>
                                                        <div class="sched-input">
                                                            <p>Schedule Date</p>
                                                            <input type="time" name="sched_time" id="" required>
                                                        </div>
                                                        <div class="sched-input-btn">
                                                            <input type="submit" name="submit_sched" value="SCHEDULE" style="background-color: blue">
                                                            <button style="background-color: red" onclick="closeSchedModal(<?php echo htmlspecialchars($new['applied_id']) ?>)" type="button">CANCEL</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="decline-modal" id="declineModal<?php echo htmlspecialchars($new['applied_id']) ?>">
                                            <div class="decline-content">
                                                <div class="decline-header">
                                                    <h4>DECLINE APPLICANT</h4>
                                                </div>
                                                <div class="decline-body">
                                                    <form action="handlers/applicant/decline_applicant.php" method="POST">
                                                        <div class="decline-input">
                                                            <input type="hidden" name="decline_applicant_id" value="<?php echo htmlspecialchars($new['applied_id']) ?>">
                                                            <textarea name="remarks" id="" placeholder="Reason why decline" required></textarea>
                                                            <p>Decline Remarks(<?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?>)</p>
                                                        </div>
                                                        <div class="decline-input-btn">
                                                            <button style="background-color:blue">CONFIRM</button>
                                                            <button style="background-color:red" type="button" onclick="closeDeclineModal(<?php echo htmlspecialchars($new['applied_id']) ?>)">CLOSE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9">No New Applicants Found</td>
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

        function showDecline(appliedId) {
            document.getElementById("declineModal" + appliedId).style.display = "flex";
        }

        function closeDeclineModal(appliedId) {
            document.getElementById("declineModal" + appliedId).style.display = "none";
        }

        function deleteConfirm(event) {
            event.preventDefault();
            if (confirm("Are you sure to delete this application?")) {
                event.target.submit();
            }
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
                const jobPosition = filterRow.children[4].querySelector("select").value.toLowerCase();
                const appliedDate = filterRow.children[5].querySelector("input[type='date']").value.toLowerCase();
                const status = filterRow.children[6].querySelector("select").value.toLowerCase();
                const program = filterRow.children[8].querySelector("select").value.toLowerCase();

                Array.from(table.rows).forEach(row => {
                    const cells = row.children;
                    const matches = [
                        matchesFirstLetter(cells[1].textContent.toLowerCase(), fullname),
                        matchesFirstLetter(cells[2].textContent.toLowerCase(), phone),
                        matchesFirstLetter(cells[3].textContent.toLowerCase(), email),
                        matchesFirstLetter(cells[4].textContent.toLowerCase(), jobPosition),
                        matchesFirstLetter(cells[5].textContent.toLowerCase(), appliedDate),
                        matchesFirstLetter(cells[6].textContent.toLowerCase(), status),
                        matchesFirstLetter(cells[8].textContent.toLowerCase(), program),
                    ];

                    row.style.display = matches.every(Boolean) ? "" : "none";
                });
            }

            function matchesFirstLetter(cellContent, filterValue) {
                if (filterValue === "") return true;
                return cellContent.startsWith(filterValue);
            }
        });
    </script>
</body>

</html>