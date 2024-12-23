<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water District</title>
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/src/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/global.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/homepage.css">
    <link rel="stylesheet" href="http://localhost/smarthr/public/css/hpAdd.css">
</head>

<body>
    <header>
        <a href="http://localhost/smarthr/" id="logoNav"><i class="fa-solid fa-droplet"></i>
            <h3>Water District</h3>
        </a>
        <nav>
            <button onclick="toggleSidebar(event)" id="toggleBtn"><i class="fa-solid fa-bars"></i></button>
            <ul id="navigation">
                <li><a href="http://localhost/smarthr/"><span>Home</span></a></li>
                <li><a href="http://localhost/smarthr/#about"><span>About</span></a></li>
                <li><a href="http://localhost/smarthr/#jobs"><span>Opportunities</span></a></li>
                <li><a href="http://localhost/smarthr/login.php"><span>Login</span></a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="view">
            <?php
            require "database/connection.php";
            if (isset($_GET['open_job'])) {
                $jobId = $_GET['open_job'];

                $fetch = $conn->prepare("SELECT * FROM jobs WHERE job_id = $jobId");
                $fetch->execute();
                $fetch_res = $fetch->get_result();
                if ($fetch_res->num_rows > 0) {
                    $jobData = $fetch_res->fetch_assoc();

                    echo '';
                }
            } else {
                header("Location: http://localhost/smarthr/");
                exit();
            }

            ?>

            <div class="wrap">
                <div class="data">
                    <table>
                        <tr>
                            <th colspan="2">JOB INFORMATION</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['job_position'] ?></p>
                                <h5>Job Position</h5>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><?php echo $jobData['plantilla_item'] ?></p>
                                <h5>Job Position</h5>
                            </td>
                            <td>
                                <p><?php echo $jobData['pay_grade'] ?></p>
                                <h5>Job Position</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['monthly_salary'] ?></p>
                                <h5>Monthly Salary</h5>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2">Qualification</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['education'] ?></p>
                                <h5>Education</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['training'] ?></p>
                                <h5>Training</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['experience'] ?></p>
                                <h5>Experience</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['eligibility'] ?></p>
                                <h5>Eligibility</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['competency'] ?></p>
                                <h5>Competency</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['open_position'] ?></p>
                                <h5>Open Position</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <p><?php echo $jobData['place'] ?></p>
                                <h5>Place</h5>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="links">
                                    <a href="http://localhost/smarthr/">BACK</a>
                                    <a href="http://localhost/smarthr/applicant/apply.php?job_id=<?php echo htmlspecialchars($jobData['job_id']) ?>">APPLY</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </section>
    </main>
    <script>
        function toggleSidebar(event) {
            event.stopPropagation();
            document.getElementById("navigation").style.display = document.getElementById("navigation").style.display === "flex" ? "none" : "flex";
        }

        window.addEventListener('resize', () => {
            if (window.innerWidth > 1000) {
                document.getElementById("navigation").style.display = "flex";
                document.getElementById("toggleBtn").style.display = "none";
            } else {
                document.getElementById("navigation").style.display = "none";
                document.getElementById("toggleBtn").style.display = "flex";
            }
        })
    </script>
</body>

</html>