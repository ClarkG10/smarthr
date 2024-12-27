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
            <div class="apply">
                <?php
                if (isset($_GET['application'])) {
                    $application_id = $_GET['application'];

                    $fetch_application = $conn->prepare("SELECT ja.*, a.*, j.* FROM job_applicants ja JOIN applicants a ON ja.applied_applicant_id = a.applicant_id JOIN jobs j ON ja.applied_job_id = j.job_id WHERE ja.applied_id = $application_id");
                    $fetch_application->execute();
                    $fetch_res = $fetch_application->get_result();
                    if ($fetch_res->num_rows === 1) {
                        $applicationData = $fetch_res->fetch_assoc();
                    }
                }
                ?>
                <div class="form">
                    <form action="handlers/apply_process.php" method="POST" class="inputForm" enctype="multipart/form-data">
                        <input type="hidden" name="applied_job_id" value="<?php echo htmlspecialchars($applicationData['job_id']) ?>">
                        <div class="head-form">
                            <h2>Application Form</h2>
                            <!-- <span>Please complete this document</span> -->
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
                                                    <input type="text" value="<?php echo htmlspecialchars($applicationData['applied_experience']) ?> years" placeholder="None" readonly>
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
                                                    <?php if (!empty($applicationData['applied_file_certificate'])): ?>
                                                        <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($applicationData['applied_file_certificate']); ?>" target="_blank">Certificate of Eligibility/Rating/License</a>
                                                    <?php else: ?>
                                                        <span>Eligibility Certificate not available</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <?php if (!empty($applicationData['applied_file_training_cert'])): ?>
                                                        <a href="http://localhost/smarthr/uploads/<?php echo htmlspecialchars($applicationData['applied_file_training_cert']); ?>" target="_blank">Certificate of Training</a>
                                                    <?php else: ?>
                                                        <span>Training Certificate not available</span>
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
    </script>
</body>

</html>