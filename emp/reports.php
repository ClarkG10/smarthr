<?php
session_start();
require "../database/connection.php";
require "handlers/authenticate.php";
require "handlers/count_all.php";
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
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/accounts.css">
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/report.css">

    <!-- CDN JQUERY AND CHARTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="accounts">
                <div class="grid">
                    <div class="item">
                        <p>Total of Applicants</p>
                        <span><?php echo $total_applied ?></span>
                        <a href="">View All</a>
                    </div>
                    <div class="item">
                        <p>Total of Candidates</p>
                        <span><?php echo $total_candidate ?></span>
                        <a href="">View All</a>
                    </div>
                    <div class="item">
                        <p>Total of Jobs</p>
                        <span><?php echo $total_job ?></span>
                        <a href="manage_jobs.php">View All</a>
                    </div>
                    <div class="item">
                        <p>Hired Applicants</p>
                        <span><?php echo $total_hired ?></span>
                        <a href="hired_applicants.php">View All</a>
                    </div>
                </div>
                <div class="body">
                    <div class="charts">
                        <div class="wrap" style="margin-bottom: 10px; grid-column:span 2; min-height: 500px">
                            <div class="label">
                                <h4>Ranking Hired Members</h4>
                            </div>
                            <div class="table">
                                <?php require "handlers/contents/report.php" ?>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Fullname</th>
                                            <th>Phone Number</th>
                                            <th>Email</th>
                                            <th>Job Position</th>
                                            <th>Ratings</th>
                                            <th>Ranked</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($newlys)): ?>
                                            <?php foreach ($newlys as $new): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($new['firstname'] . " " . $new['lastname']) ?></td>
                                                    <td><?php echo htmlspecialchars($new['phonenumber']) ?></td>
                                                    <td><?php echo htmlspecialchars($new['email']) ?></td>
                                                    <td style="text-transform:uppercase"><?php echo htmlspecialchars($new['job_position']) ?></td>
                                                    <td><?php echo htmlspecialchars($new['applied_ratings']) ?></td>
                                                    <td><?php echo $newCount++ ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8">No Ranked Applicant Found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="wrap" style="overflow:auto">
                            <?php require "handlers/contents/chart1.php" ?>
                            <!-- BAR GRAPH FOR ALL JOB APPLICANT BY THE JOBS -->
                            <canvas id="jobApplicantsChart" width="400" height="200"></canvas>
                            <script>
                                // Setup Block
                                const jobPositions = <?php echo json_encode($jobPositions); ?>;
                                const jobApplicants = <?php echo json_encode($numApplicantsArray); ?>;

                                const data = {
                                    labels: jobPositions,
                                    datasets: [{
                                        label: 'Total Job Applicants',
                                        data: jobApplicants,
                                        borderWidth: 1,
                                    }]
                                };
                                // Config Block
                                const config = {
                                    type: 'bar',
                                    data: data,
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true, // Ensure the Y-axis starts at 0
                                                title: {
                                                    display: true,
                                                    text: 'Number of Applicants'
                                                },
                                                ticks: {
                                                    stepSize: 1, // Force the Y-axis ticks to be in integer steps
                                                    callback: function(value) {
                                                        return Number.isInteger(value) ? value : ''; // Only show integers on Y-axis
                                                    }
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Job Positions'
                                                },
                                                ticks: {
                                                    font: {
                                                        size: 8
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false,
                                            }
                                        }
                                    }
                                };
                                // Render Block
                                const jaChart = new Chart(
                                    document.getElementById("jobApplicantsChart"),
                                    config
                                );
                            </script>
                        </div>
                        <div class="wrap" style="overflow:auto">
                            <?php require "handlers/contents/chart2.php" ?>
                            <!-- Dropdown for selecting a year -->
                            <div class="year-selection">
                                <form method="GET">
                                    <label for="year">Select Year: </label>
                                    <select name="year" id="year" onchange="this.form.submit()">
                                        <?php for ($year = $currentYear; $year <= $maxYear; $year++) : ?>
                                            <option value="<?= $year ?>" <?= ($year == $selectedYear) ? 'selected' : '' ?>>
                                                <?= $year ?>
                                            </option>
                                        <?php endfor; ?>
                                    </select>
                                </form>
                            </div>
                            <canvas id="applicantsChart" width="400" height="200"></canvas>
                            <script>
                                // PHP data to JavaScript
                                const applicantsData = <?php echo json_encode($applicantCounts); ?>;
                                const selectedYear = <?php echo $selectedYear; ?>;

                                // Setup Block
                                const monthData = {
                                    labels: [
                                        'January', 'February', 'March', 'April', 'May', 'June',
                                        'July', 'August', 'September', 'October', 'November', 'December'
                                    ],
                                    datasets: [{
                                        label: `Total Applicants for ${selectedYear}`,
                                        data: applicantsData,
                                        borderWidth: 2,
                                    }]
                                };

                                // Config Block
                                const monthConfig = {
                                    type: 'line',
                                    data: monthData,
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true, // Ensure the Y-axis starts at 0
                                                title: {
                                                    display: true,
                                                    text: 'Number of Applicants'
                                                },
                                                ticks: {
                                                    stepSize: 1, // Force the Y-axis ticks to be in integer steps
                                                    callback: function(value) {
                                                        return Number.isInteger(value) ? value : ''; // Only show integers on Y-axis
                                                    }
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Month'
                                                },
                                                ticks: {
                                                    font: {
                                                        size: 10
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false,
                                            }
                                        }
                                    }
                                };

                                // Render Block
                                const applicantsChart = new Chart(
                                    document.getElementById("applicantsChart"),
                                    monthConfig
                                );
                            </script>
                        </div>

                        <div class="wrap" style="overflow:auto; margin-top:10px">
                            <?php require "handlers/contents/chart3.php" ?>
                            <!-- BAR GRAPH FOR AGE OF JOB APPLICANT -->
                            <canvas id="ageApplicantsChart" width="400" height="200"></canvas>
                            <script>
                                // Setup Block
                                const ages = <?php echo $ages_json; ?>;
                                const ageJa = <?php echo $counts_json; ?>;
                                const ageJaRounded = ageJa.map(count => Math.round(count));

                                const ageData = {
                                    labels: ages, // Using the 'ages' array as labels
                                    datasets: [{
                                        label: 'Age of Applicants',
                                        data: ageJaRounded, // Number of applicants for each age
                                        borderWidth: 1,
                                    }]
                                };

                                const ageConfig = {
                                    type: 'bar',
                                    data: ageData,
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Number of Applicants'
                                                },
                                                ticks: {
                                                    stepSize: 1,
                                                    callback: function(value) {
                                                        return Number.isInteger(value) ? value : '';
                                                    }
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Age'
                                                },
                                                ticks: {
                                                    font: {
                                                        size: 12
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(tooltipItem) {
                                                        return 'Applicants: ' + tooltipItem.raw;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                };
                                // Render Block
                                const ageChart = new Chart(
                                    document.getElementById("ageApplicantsChart"),
                                    ageConfig
                                );
                            </script>
                        </div>
                        <div class="wrap" style="overflow:auto; margin-top:10px">
                            <?php require "handlers/contents/chart4.php" ?>
                            <canvas id="genderApplicantsChart" width="400" height="200"></canvas>
                            <script>
                                const genderApplicantsData = <?php echo json_encode($applicantCounts); ?>;

                                const genderData = {
                                    labels: ['Male', 'Female'],
                                    datasets: [{
                                        label: `Gender of Applicants`,
                                        data: genderApplicantsData,
                                        borderWidth: 1,
                                    }]
                                };
                                // Config Block 
                                const genderConfig = {
                                    type: 'bar',
                                    data: genderData,
                                    options: {
                                        responsive: true,
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Number of Applicants'
                                                },
                                                ticks: {
                                                    stepSize: 1,
                                                    callback: function(value) {
                                                        return Number.isInteger(value) ? value : '';
                                                    }
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Gender'
                                                },
                                                ticks: {
                                                    font: {
                                                        size: 12
                                                    }
                                                }
                                            }
                                        },
                                        plugins: {
                                            tooltip: {
                                                mode: 'index',
                                                intersect: false,
                                            }
                                        }
                                    }
                                };
                                // Render Block for Gender Chart
                                const genderChart = new Chart(
                                    document.getElementById("genderApplicantsChart"),
                                    genderConfig
                                );
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
</body>

</html>