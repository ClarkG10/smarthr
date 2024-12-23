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
        <section class="home" id="home">
            <h1>Welcome to Water District</h1>
            <h5>Providing clean, safe water to our community for generations.</h5>
        </section>
        <section class="about" id="about">
            <div class="label">
                <h1>Our Services <i class="fa-solid fa-arrow-down"></i></h1>
            </div>
            <div class="gridItem">
                <div class="item">
                    <i class="fa-solid fa-house"></i>
                    <h3>Residential Water</h3>
                    <span>Clean, reliable water for your home, 24/7.</span>
                </div>
                <div class="item">
                    <i class="fa-solid fa-leaf"></i>
                    <h3>Conservation</h3>
                    <span>Programs to help you save water and money.</span>
                </div>
                <div class="item">
                    <i class="fa-solid fa-phone"></i>
                    <h3>24/7 Support</h3>
                    <span>Always here to help with your water needs.</span>
                </div>
            </div>
        </section>
        <section class="jobs" id="jobs">
            <?php
            require "database/connection.php";
            $openJobs = $conn->prepare("SELECT * FROM jobs WHERE status = 'Open'");
            $openJobs->execute();
            $openResult = $openJobs->get_result();

            $jobs = [];
            while ($openRow = $openResult->fetch_assoc()) {
                $jobs[] = $openRow;
            }
            ?>
            <!-- DISPLAYED OPEN JOB OPPORTUNITIES -->
            <?php if (!empty($jobs)): ?>
                <h2>OPEN JOB OPPORTUNITIES <i class="fa-solid fa-arrow-down"></i></h2>
                <?php foreach ($jobs as $job): ?>
                    <div class="wrap">
                        <a href="view.php?open_job=<?php echo htmlspecialchars($job['job_id']) ?>">
                            <h3><?php echo htmlspecialchars($job['job_position']) ?></h3>
                            <p><strong>Place of Work :</strong> <?php echo htmlspecialchars($job['place']) ?></p>
                            <span> <strong>Open Position :</strong> <?php echo htmlspecialchars($job['open_position']) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="wrap">
                    <p style="color:red">No Open Job Opportunities</p>
                </div>
            <?php endif; ?>
        </section>
    </main>
    <footer style="text-align:center">
        <p>© 2024 Water District. All rights reserved.</p>
    </footer>

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