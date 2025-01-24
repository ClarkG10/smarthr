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
    <!-- APPLICANT CSS CONTENTS -->
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/navigations.css">
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/opportunities.css">
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/addFrom.css">
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/additional.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div style="display: flex; justify-content: end; align-items: center; padding: 10px;">
                <!-- Button to trigger modal -->
                <button type="button" style="color: white; background-color: blue; padding: 5px; border-radius: 5px; border: none" id="updateScoresBtn">Update Scores</button>
            </div>
            <div class="apply">
                <?php
                if (isset($_GET['application'])) {
                    $application_id = $_GET['application'];

                    // Fetch application and applicant details
                    $fetch_application = $conn->prepare("SELECT ja.*, a.*, j.*, ascores.* FROM job_applicants ja 
                                              JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                                              JOIN jobs j ON ja.applied_job_id = j.job_id
                                              LEFT JOIN applicant_scores ascores ON ascores.applied_id = ja.applied_id
                                              WHERE ja.applied_id = ?");
                    $fetch_application->bind_param("i", $application_id);
                    $fetch_application->execute();
                    $fetch_res = $fetch_application->get_result();

                    if ($fetch_res->num_rows === 1) {
                        $applicationData = $fetch_res->fetch_assoc();
                    }
                }
                ?>

                <div id="updateScoresModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center;">
                    <div style="background: white; padding: 20px; border-radius: 8px; width: 80%; max-width: 400px; position: relative;">
                        <div style="display: flex; justify-content: space-between;">
                            <h3>Update Scores</h3>
                            <button onclick="closeModal()" style="background-color: white; font-weight: 700; border: none; padding: 5px; border-radius: 5px">X</button>
                        </div>
                        <form action="handlers/applicant/update_scores.php" method="POST" onsubmit="return validateScores()">
                            <input type="hidden" name="applied_id" value="<?php echo htmlspecialchars($applicationData['applied_id']) ?>">

                            <div>
                                <label for="resume_points">Resume Points:</label><br>
                                <input style="width: 100%" type="number" id="resume_points" name="resume_points" value="<?php echo htmlspecialchars($applicationData['resume_points'] ?? '') ?>" required>
                            </div>
                            <div>
                                <label for="education_points">Education Points:</label><br>
                                <input style="width: 100%" type="number" id="education_points" name="education_points" value="<?php echo htmlspecialchars($applicationData['education_points'] ?? '') ?>" required>
                            </div>
                            <div>
                                <label for="training_points">Training Points:</label><br>
                                <input style="width: 100%" type="number" id="training_points" name="training_points" value="<?php echo htmlspecialchars($applicationData['training_points'] ?? '') ?>" required>
                            </div>
                            <div>
                                <label for="experience_points">Experience Points:</label><br>
                                <input style="width: 100%" type="number" id="experience_points" name="experience_points" value="<?php echo htmlspecialchars($applicationData['experience_points'] ?? '') ?>" required>
                            </div>
                            <div>
                                <label for="eligibility_points">Eligibility Points:</label><br>
                                <input style="width: 100%" type="number" id="eligibility_points" name="eligibility_points" value="<?php echo htmlspecialchars($applicationData['eligibility_points'] ?? '') ?>" required>
                            </div>
                            <div>
                                <label for="competency_points">Competency Points:</label><br>
                                <input style="width: 100%" type="number" id="competency_points" name="competency_points" value="<?php echo htmlspecialchars($applicationData['competency_points'] ?? '') ?>" required>
                            </div>
                            <div>
                                <label for="skill_points">Skill Points:</label><br>
                                <input style="width: 100%" type="number" id="skill_points" name="skill_points" value="<?php echo htmlspecialchars($applicationData['skill_points'] ?? '') ?>" required>
                            </div>
                            <br>
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <p for="skill_points">Partial Rating:</p>
                                    <p><?php echo htmlspecialchars($applicationData['partial_rating'] ?? '') ?></p>
                                </div>
                                <div>
                                    <p for="skill_points">Final Rating:</p>
                                    <p><?php echo htmlspecialchars($applicationData['applied_ratings'] ?? '') ?></p>
                                </div>
                            </div>

                            <div id="maxScoresPopover" style="display: none; background: #f9f9f9; padding: 10px; border: 1px solid #ccc; margin-top: 10px; border-radius: 5px; position: absolute; top: -10px; left: 102%; z-index: 9999; width: 400px;">
                                <h4>Max Points</h4>
                                <ul>
                                    <li>Resume: 100</li>
                                    <li>Education: 20</li>
                                    <li>Training: 10</li>
                                    <li>Experience: 30</li>
                                    <li>Eligibility: 15</li>
                                    <li>Competency: 15</li>
                                    <li>Skills: 10</li>
                                </ul>
                                <br>
                                <h4>Formula:</h4>
                                <p><strong>Partial Rating:</strong> ((Education + Training + Experience + Eligibility + Competency + Skills) / 100) * 50</p>
                                <p><strong>Resume Points:</strong> (Resume Points / 100) * 50</p><br>
                                <p><strong>Final Rating:</strong> Partial Score + Resume Score</p>
                            </div>

                            <div style="margin-top: 15px; display: flex; justify-content: space-between;">
                                <button type="button" onclick="toggleMaxScores()" style="background-color: lightgray; border: none; padding: 5px; border-radius: 5px;">Show Max Scores and Formula</button>
                                <button type="submit" style="background-color: blue; color: white; border: none; padding: 5px; border-radius: 5px;">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="form">
                    <form action="handlers/apply_process.php" method="POST" class="inputForm" enctype="multipart/form-data">
                        <input type="hidden" name="applied_job_id" value="<?php echo htmlspecialchars($applicationData['job_id']) ?>">
                        <div style="display: flex; justify-content: space-between; align-items: center">
                            <div class="head-form">
                                <h2>Application Form</h2>
                                <!-- <span>Please complete this document</span> -->
                            </div>

                        </div>
                        <div class="body-form">
                            <div class="item">
                                <div class="table">
                                    <table>
                                        <tr>
                                            <td colspan="3"><strong>Applicant Information</strong></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($applicationData['firstname']) ?>" readonly>
                                                    <p>First name</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($applicationData['middlename']) ?>" readonly>
                                                    <p>Middle name</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($applicationData['lastname']) ?>" readonly>
                                                    <p>Last name</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($applicationData['age']) ?>" readonly>
                                                    <p>Age</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($applicationData['gender']) ?>" readonly>
                                                    <p>Gender</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($applicationData['phonenumber']) ?>" readonly>
                                                    <p>Phone Number</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">Current Address</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="td">
                                                    <input type="text" name="street" value="<?php echo htmlspecialchars($applicationData['streets']) ?>" placeholder="None" readonly>
                                                    <p>Street/Barangay/Municipality</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="city" value="<?php echo htmlspecialchars($applicationData['city']) ?>" placeholder="None" readonly>
                                                    <p>City</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="province" value="<?php echo htmlspecialchars($applicationData['province']) ?>" placeholder="None" readonly>
                                                    <p>Province</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="zip_code" value="<?php echo htmlspecialchars($applicationData['postal_code']) ?>" placeholder="None" readonly>
                                                    <p>Postal/Zip Code</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="zip_code" value="<?php echo htmlspecialchars($applicationData['applied_status']) ?>" placeholder="None" readonly>
                                                    <p>Status</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['email']) ?>" readonly>
                                                    <p>Email</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="home_number" value="<?php echo htmlspecialchars($applicationData['home_phone']) ?>" placeholder="None" readonly>
                                                    <p>Home Number</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="facebook_profile" value="<?php echo htmlspecialchars($applicationData['facebook_link']) ?>" placeholder="None" readonly>
                                                    <p>Facebook Profile Link</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="item">
                                <div class="table">
                                    <table>
                                        <tr>
                                            <td colspan="3"><strong>Job Information</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['job_position']) ?>" readonly>
                                                    <p>Applied Job for</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['plantilla_item']) ?>" readonly>
                                                    <p>Plantilla Item</p>
                                                </div>
                                            </td>
                                            <td style="width: 50%">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['pay_grade']) ?>" readonly>
                                                    <p>Pay Grade</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['place']) ?>" readonly>
                                                    <p>Place of Work</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" value="" readonly>
                                                    <p>Closing Date</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <textarea type="text" value="" rows="5" readonly><?php echo htmlspecialchars($applicationData['job_description']) ?></textarea>
                                                    <p>Job Description</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="item">
                                <div class="table">
                                    <table>
                                        <tr>
                                            <td colspan="3"><strong>Qualification</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_education']) ?>" placeholder="None" readonly>
                                                    <p>Education</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_program']) ?>" placeholder="None" readonly>
                                                    <p>Program</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_training']) ?>" placeholder="None" readonly>
                                                    <p>Training</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_experience']) ?>" placeholder="None" readonly>
                                                    <p>Experience</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_eligibility']) ?>" placeholder="None" readonly>
                                                    <p>Eligibility</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_competency']) ?>" placeholder="None" readonly>
                                                    <p>Competency</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="item">
                                <div class="table">
                                    <table>
                                        <tr>
                                            <td colspan="3"><strong>Submitted Documents</strong></td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php if (!empty($applicationData['applied_resume'])): ?>
                                                        <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($applicationData['applied_resume']); ?>" target="_blank">Resume</a>
                                                    <?php else: ?>
                                                        <span>Resume not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php if (!empty($applicationData['applied_file_pds'])): ?>
                                                        <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($applicationData['applied_file_pds']); ?>" target="_blank">Personal Data Sheet (PDS)</a>
                                                    <?php else: ?>
                                                        <span>PDS not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php if (!empty($applicationData['applied_file_rating'])): ?>
                                                        <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($applicationData['applied_file_rating']); ?>" target="_blank">Performance Rating</a>
                                                    <?php else: ?>
                                                        <span>Performance Rating not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php
                                                    $certificates = json_decode($applicationData['applied_file_certificate'], true);

                                                    if (!empty($certificates) && is_array($certificates)): ?>
                                                        Eligibility Certificates:
                                                        <?php foreach ($certificates as $certificate): ?>
                                                            <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($certificate); ?>" target="_blank">
                                                                <?php echo htmlspecialchars(basename($certificate)); ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span>Eligibility Certificates not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php
                                                    $trainingCerts = json_decode($applicationData['applied_file_training_cert'], true);

                                                    if (!empty($trainingCerts) && is_array($trainingCerts)): ?>
                                                        Training Certificates:
                                                        <?php foreach ($trainingCerts as $trainingCert): ?>
                                                            <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($trainingCert); ?>" target="_blank">
                                                                <?php echo htmlspecialchars(basename($trainingCert)); ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <span>Training Certificates not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php if (!empty($applicationData['applied_file_tor'])): ?>
                                                        <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($applicationData['applied_file_tor']); ?>" target="_blank">Transcript of Records</a>
                                                    <?php else: ?>
                                                        <span>Transcript of Records not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <!-- FOR SCHEDULE DETAILS -->
                            <?php if ($applicationData['sched_status'] !== "Declined"): ?>
                                <?php
                                $fetch_sched = $conn->prepare("SELECT * FROM schedules WHERE schedule_applied_id = $application_id");
                                $fetch_sched->execute();
                                $sched_res = $fetch_sched->get_result();

                                if ($sched_res->num_rows > 0) {
                                    $scheduleData = $sched_res->fetch_assoc();

                                    echo '<div class="item">
                                            <div class="table">
                                                <table>
                                                    <tr>
                                                        <td colspan="3"><strong>Scheduled Date and Time</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Date :</strong> ' . $scheduleData['schedule_date'] . ' </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Time :</strong> ' . $scheduleData['schedule_time'] . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Note :</strong> Be exact on date and time given</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>';
                                } else {
                                    echo "Not yet Schedule";
                                }
                                ?>
                            <?php endif; ?>

                            <?php if ($applicationData['sched_status'] === "Declined"): ?>
                                <div class="item">
                                    <div class="table">
                                        <table>
                                            <tr>
                                                <td colspan="3" style="color:red"><strong>Declined Application</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Reason :</strong> <?php echo htmlspecialchars($applicationData['remarks']) ?> </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="apply-buttons">
                            <a href="http://localhost/smarthr/emp/application.php">BACK</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>

    </main>


    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
    <script>
        function showViewModal(jobId) {
            document.getElementById("viewModal" + jobId).style.display = "flex";
        }

        function closeViewModal(jobId) {
            document.getElementById("viewModal" + jobId).style.display = "none";
        }

        document.getElementById("updateScoresBtn").addEventListener("click", function() {
            document.getElementById("updateScoresModal").style.display = "flex";
        });

        function closeModal() {
            document.getElementById("updateScoresModal").style.display = "none";
        }

        function validateScores() {
            const maxValues = {
                resume_points: 100,
                education_points: 20,
                training_points: 10,
                experience_points: 30,
                eligibility_points: 15,
                competency_points: 15,
                skill_points: 10,
            };

            for (const [key, max] of Object.entries(maxValues)) {
                const input = document.getElementById(key);
                if (parseFloat(input.value) > max) {
                    alert(`${key.replace('_', ' ')} cannot exceed ${max}`);
                    return false;
                }
            }

            return true;
        }

        function toggleMaxScores() {
            const popover = document.getElementById("maxScoresPopover");
            if (popover.style.display === "none" || popover.style.display === "") {
                popover.style.display = "block";
            } else {
                popover.style.display = "none";
            }
        }
    </script>
</body>

</html>