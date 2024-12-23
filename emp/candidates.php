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
                    <div class="label" style="display: flex; align-items:center; justify-content:space-between">
                        <h4>ALL CANDIDATE APPLICANTS</h4>
                        <a href="hired_applicants.php" style="background-color:#2563eb; font-weight:600;color:white; padding:5px 10px; border-radius: 5px; font-size:14px">View Hired</a>
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
                                    <th>Ratings</th>
                                    <th>Ranked</th>
                                    <th style="width: 200px">Action</th>
                                </tr>
                                <tr id="filterSearch">
                                    <td><input type="search" id="filter_fullname" placeholder="Search by Fullname"></td>
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
                                    <td><input type="date" id="filter_date"></td>
                                    <td></td>
                                    <td>
                                        <select id="filter_status">
                                            <option value="" selected>Select Status</option>
                                            <option value="Approve">Approve</option>
                                            <option value="Awaiting">Awaiting</option>
                                        </select>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </thead>
                            <?php
                            $newApp = $conn->prepare("SELECT c.*, ja.*, a.*, j.* FROM candidates c JOIN job_applicants ja ON c.candidate_applied_id = ja.applied_id JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                            JOIN jobs j ON ja.applied_job_id = j.job_id WHERE c.candidate_status <> 'Approved' AND c.candidate_status <> 'Declined'  ORDER BY ja.applied_ratings DESC");
                            // $newApp = $conn->prepare("SELECT c.*, ja.*, a.*, j.* FROM candidates c JOIN job_applicants ja ON c.candidate_applied_id = ja.applied_id JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                            // JOIN jobs j ON ja.applied_job_id = j.job_id ORDER BY ja.applied_ratings DESC");
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
                                            <td style="color: 
                                            <?php
                                            if ($new['candidate_status'] == 'Awaiting') {
                                                echo 'blue';
                                            } elseif ($new['candidate_status'] == 'Approved') {
                                                echo 'green';
                                            } elseif ($new['candidate_status'] == 'Declined') {
                                                echo 'red';
                                            }
                                            ?>">
                                                <?php echo htmlspecialchars($new['candidate_status']); ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($new['applied_ratings']) ?></td>
                                            <td><?php echo $newCount++ ?></td>
                                            <td>
                                                <?php if ($new['candidate_status'] !== 'Approved' && $new['candidate_status'] !== 'Declined'): ?>
                                                    <div class="action-btn">
                                                        <button style="background-color: blue" onclick="showApprove(<?php echo htmlspecialchars($new['candidate_id']) ?>)">APPROVE</button>
                                                        <button style="background-color: red" type="button" id="deleteActBtn" onclick="showDecline(<?php echo htmlspecialchars($new['candidate_id']) ?>)">DECLINE</button>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                        <div class="approve-modal" id="approveModal<?php echo htmlspecialchars($new['candidate_id']) ?>">
                                            <div class="approve-content">
                                                <div class="approve-header">
                                                    <h4>APPROVE APPLICANTS</h4>
                                                </div>
                                                <div class="approve-body">
                                                    <form action="handlers/applicant/approved.php" method="POST">
                                                        <input type="hidden" name="approve_candidate_id" value="<?php echo htmlspecialchars($new['candidate_id']) ?>">
                                                        <div class="approve-input">
                                                            <textarea name="remarks" id="" placeholder="(Remarks)"></textarea>
                                                            <p>Approved Remarks(<?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?>)</p>
                                                        </div>
                                                        <div class="approve-input-btn">
                                                            <button style="background-color:blue">CONFIRM</button>
                                                            <button style="background-color:red" type="button" onclick="closeApproveModal(<?php echo htmlspecialchars($new['candidate_id']) ?>)">CLOSE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="decline-modal" id="declineModal<?php echo htmlspecialchars($new['candidate_id']) ?>">
                                            <div class="decline-content">
                                                <div class="decline-header">
                                                    <h4>DECLINE CANDIDATE</h4>
                                                </div>
                                                <div class="decline-body">
                                                    <form action="handlers/applicant/declined.php" method="POST">
                                                        <input type="hidden" name="decline_candidate_id" value="<?php echo htmlspecialchars($new['candidate_id']) ?>">
                                                        <div class="decline-input">
                                                            <textarea name="remarks" id="" placeholder="Reason why decline" required></textarea>
                                                            <p>Decline Remarks(<?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?>)</p>
                                                        </div>
                                                        <div class="decline-input-btn">
                                                            <button style="background-color:blue">CONFIRM</button>
                                                            <button style="background-color:red" type="button" onclick="closeDeclineModal(<?php echo htmlspecialchars($new['candidate_id']) ?>)">CLOSE</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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

                Array.from(table.rows).forEach(row => {
                    const cells = row.children;
                    const matches = [
                        matchesFirstLetter(cells[1].textContent.toLowerCase(), fullname),
                        matchesFirstLetter(cells[2].textContent.toLowerCase(), phone),
                        matchesFirstLetter(cells[3].textContent.toLowerCase(), email),
                        matchesFirstLetter(cells[4].textContent.toLowerCase(), jobPosition),
                        matchesFirstLetter(cells[5].textContent.toLowerCase(), appliedDate),
                        matchesFirstLetter(cells[6].textContent.toLowerCase(), status),
                    ];

                    row.style.display = matches.every(Boolean) ? "" : "none";
                });
            }

            function matchesFirstLetter(cellContent, filterValue) {
                if (filterValue === "") return true;
                return cellContent.startsWith(filterValue); // Check if the cell starts with the filter value
            }
        });
    </script>
</body>

</html>