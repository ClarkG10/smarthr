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
    <!-- <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/form.css"> -->
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/addFrom.css">
</head>
<style>
    .not-allowed {
        cursor: not-allowed !important;
        opacity: 80% !important;
    }
</style>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="apply">
                <?php
                if (isset($_GET['job_id'])) {
                    $jobId = $_GET['job_id'];

                    $fetchJob = $conn->prepare("SELECT * FROM jobs WHERE job_id = $jobId");
                    $fetchJob->execute();
                    $fetchJob_res = $fetchJob->get_result();

                    if ($fetchJob_res->num_rows === 1) {
                        $jobInfo = $fetchJob_res->fetch_assoc();
                    }
                }
                ?>
                <div class="form">
                    <form action="handlers/apply_process.php" method="POST" class="inputForm" enctype="multipart/form-data">
                        <input type="hidden" name="applied_job_id" value="<?php echo htmlspecialchars($jobInfo['job_id']) ?>">
                        <input type="hidden" name="job_position" value="<?php echo htmlspecialchars($jobInfo['job_position']) ?>">
                        <input type="hidden" name="job_minimum_education" value="<?php echo htmlspecialchars($jobInfo['education']) ?>">
                        <input type="hidden" name="job_minimum_training" value="<?php echo htmlspecialchars($jobInfo['training']) ?>">
                        <input type="hidden" name="job_minimum_experience" value="<?php echo htmlspecialchars($jobInfo['experience']) ?>">
                        <input type="hidden" name="job_minimum_eligibility" value="<?php echo htmlspecialchars($jobInfo['eligibility']) ?>">
                        <input type="hidden" name="job_minimum_competency" value="<?php echo htmlspecialchars($jobInfo['competency']) ?>">
                        <input type="hidden" name="job_minimum_skills" value="<?php echo htmlspecialchars($jobInfo['job_skills']) ?>">
                        <input type="hidden" name="job_description" value="<?php echo htmlspecialchars($jobInfo['job_description']) ?>">
                        <div class="head-form">
                            <h2>Application Form</h2>
                            <span>Please complete this document</span>
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
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($userInfo['firstname']) ?>" readonly>
                                                    <p>First name</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($userInfo['middlename']) ?>" readonly>
                                                    <p>Middle name</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($userInfo['lastname']) ?>" readonly>
                                                    <p>Last name</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($userInfo['age']) ?>" readonly>
                                                    <p>Age</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($userInfo['gender']) ?>" readonly>
                                                    <p>Gender</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="" value="<?php echo htmlspecialchars($userInfo['phonenumber']) ?>" readonly>
                                                    <p>Phone Number</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3"><strong>Current Address</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <div class="td">
                                                    <input type="text" name="street" placeholder="Enter Info" required>
                                                    <p>Street/Barangay/Municipality</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="city" placeholder="Enter Info" required>
                                                    <p>City</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="province" placeholder="Enter Info" required>
                                                    <p>Province</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="zip_code" placeholder="Enter Info" required>
                                                    <p>Postal/Zip Code</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <select name="status" id="" required>
                                                        <option value="" selected disabled>Select Status</option>
                                                        <option value="Single">Single</option>
                                                        <option value="Married">Married</option>
                                                    </select>
                                                    <p>Status</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td">
                                                    <input type="text" value="<?php echo htmlspecialchars($userInfo['email']) ?>" readonly>
                                                    <p>Email</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="home_number" placeholder="Enter Info">
                                                    <p>Home Number</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td">
                                                    <input type="text" name="facebook_profile" placeholder="Enter Info">
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
                                            <td colspan="3"><strong>Details of Job Opportunity</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td not-allowed">
                                                    <input type="text" value="<?php echo htmlspecialchars($jobInfo['job_position']) ?>" class="not-allowed" readonly>
                                                    <p>Applied Job for</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%">
                                                <div class="td not-allowed">
                                                    <input type="text" value="<?php echo htmlspecialchars($jobInfo['plantilla_item']) ?>" class="not-allowed" readonly>
                                                    <p>Plantilla Item</p>
                                                </div>
                                            </td>
                                            <td style="width: 50%">
                                                <div class="td not-allowed">
                                                    <input type="text" value="<?php echo htmlspecialchars($jobInfo['pay_grade']) ?>" class="not-allowed" readonly>
                                                    <p>Pay Grade</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="td not-allowed">
                                                    <input type="text" value="<?php echo htmlspecialchars($jobInfo['place']) ?>" class="not-allowed" readonly>
                                                    <p>Place of Work</p>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="td not-allowed">
                                                    <input type="text" value="" class="not-allowed" readonly>
                                                    <p>Closing Date</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td not-allowed">
                                                    <textarea type="text" value="" rows="5" class="not-allowed" readonly><?php echo htmlspecialchars($jobInfo['job_description']) ?></textarea>
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
                                                    <select name="education" id="education-level" required>
                                                        <option value="" selected disabled>Select Education Level</option>
                                                        <option value="Elementary">Elementary</option>
                                                        <option value="High School">High School</option>
                                                        <option value="Vocational">Vocational</option>
                                                        <option value="College">College</option>
                                                        <option value="Postgraduate">Postgraduate</option>
                                                    </select>
                                                    <p>Education</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="program-selection" style="display: none;">
                                            <td colspan="3">
                                                <div class="td">
                                                    <select name="program" id="program-options">
                                                    </select>
                                                    <input
                                                        type="text"
                                                        id="other-program"
                                                        name="other-program"
                                                        placeholder="Specify other program"
                                                        style="display: none; margin-top: 10px;"
                                                        value="" />
                                                    <p>Program</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="text" name="competency" placeholder="Enter competencies" autocomplete="off">
                                                    <p>Competency</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <select name="experience" required>
                                                        <option value="" selected disabled>Select Years of Experience</option>
                                                        <option value="0 Less than 1 year">Less than 1 year</option>
                                                        <option value="1 year">1 year</option>
                                                        <option value="2 years">2 years</option>
                                                        <option value="3 years">3 years</option>
                                                        <option value="4 years">4 years</option>
                                                        <option value="5 years">5 years</option>
                                                        <option value="6 years">6 years</option>
                                                        <option value="7 years">7 years</option>
                                                        <option value="8 years">8 years</option>
                                                        <option value="9 years">9 years</option>
                                                        <option value="10-14 years">10-14 years</option>
                                                        <option value="15-19 years">15-19 years</option>
                                                        <option value="20+ years">20+ years</option>
                                                    </select>
                                                    <p>Experience</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="td">
                                                    <input name="skills" type="text" placeholder="Enter skills" autocomplete="off">
                                                    <p>Skills</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 75%;">
                                                <div class="td">
                                                    <input type="text" name="eligibility" placeholder="Enter eligibility" autocomplete="off">
                                                    <p>Eligibility</p>
                                                </div>
                                            </td>
                                            <td style="width: 25%;">
                                                <div class="td">
                                                    <input type="file" id="file-certificate" name="file-certificate[]" accept=".pdf,.docx" multiple>
                                                    <p>Certificate of Eligibility/Rating/License : (optional) :</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 75%; ">
                                                <div class="td">
                                                    <input type="text" name="training" placeholder="Enter training details (optional)" autocomplete="off">
                                                    <p>Training</p>
                                                </div>
                                            </td>
                                            <td style="width: 25%;">
                                                <div class="td">
                                                    <input type="file" id="file-certificate-training" name="file-certificate-training[]" accept=".pdf,.docx" multiple>
                                                    <p>Provide Certificate of the training : (optional) :</p>
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
                                            <td colspan="3"><strong>Required Document</strong></td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="file" id="resume" name="resume" accept=".pdf,.docx" required>
                                                    <p>Upload Resume (PDF, DOCX) : (Required)</p>
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
                                            <td colspan="3"><strong>Additional Documents</strong></td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="file" id="file-pds" name="file-pds" accept=".pdf,.docx">
                                                    <p>Personnal Data Sheet(PDS) : (Optional, PDF, DOCX) :</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="file" id="file-rating" name="file-rating" accept=".pdf,.docx">
                                                    <p>Performance Rating : (if applicable.optional) :</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="border-bottom:1px solid rgba(0,0,0,0.5)">
                                            <td colspan="3">
                                                <div class="td">
                                                    <input type="file" id="file-tor" name="file-tor" accept=".pdf,.docx">
                                                    <p>Transcript of Records : (optional) :</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="apply-buttons">
                                <a href="">BACK</a>
                                <button type="submit">SUBMIT</button>
                            </div>
                    </form>
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
    <script>
        const educationLevel = document.getElementById('education-level');
        const programSelection = document.getElementById('program-selection');
        const programOptions = document.getElementById('program-options');
        const otherProgramInput = document.getElementById('other-program');

        // Program options
        const programs = {
            Vocational: [
                "Select a program",
                "Plumbing Technology",
                "Electrical Installation and Maintenance",
                "Refrigeration and Air-conditioning Servicing",
                "Construction Technology",
                "Heavy Equipment Operation",
                "Industrial Engineering Technology",
                "Graphic Design",
                "Others"
            ],
            College: [
                "Select a program",
                "Bachelor of Science in Civil Engineering",
                "Bachelor of Science in Environmental Science",
                "Bachelor of Science in Public Administration",
                "Bachelor of Science in Business Administration",
                "Bachelor of Science in Mechanical Engineering",
                "Bachelor of Science in Electrical Engineering",
                "Bachelor of Science in Geology",
                "Bachelor of Science in Statistics",
                "Bachelor of Science in Information Technology",
                "Bachelor of Science in Accounting",
                "Bachelor of Science in Marketing",
                "Bachelor of Science in Human Resource Management",
                "Bachelor of Science in Chemistry",
                "Bachelor of Science in Physics",
                "Bachelor of Science in Economics",
                "Bachelor of Science in Urban Planning",
                "Others"
            ],
            Postgraduate: [
                "Select a program",
                "Master of Public Administration",
                "Master of Science in Environmental Science",
                "Master of Science in Civil Engineering",
                "Doctor of Philosophy in Environmental Science",
                "Doctor of Philosophy in Civil Engineering",
                "Master of Business Administration",
                "Master of Science in Urban Planning",
                "Master of Science in Project Management",
                "Master of Science in Water Resource Management",
                "Master of Science in Finance",
                "Others"
            ]
        };

        educationLevel.addEventListener('change', () => {
            const selectedLevel = educationLevel.value;

            if (programs[selectedLevel]) {
                programSelection.style.display = 'table-row';
                programOptions.innerHTML = '';

                programs[selectedLevel].forEach(program => {
                    const option = document.createElement('option');
                    option.value = program === "Select a program" ? "N/A" : program;
                    option.textContent = program;
                    programOptions.appendChild(option);
                });
            } else {
                programSelection.style.display = 'none';
            }
            otherProgramInput.style.display = 'none';
        });

        // Show input if "Others" is selected
        programOptions.addEventListener('change', () => {
            if (programOptions.value === "Others") {
                otherProgramInput.style.display = 'block';
            } else {
                otherProgramInput.style.display = 'none';
            }
        });
    </script>
</body>

</html>