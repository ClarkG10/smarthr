<div class="topbar">
    <button id="toggleSidebar" onclick="toggleSidebar(event)"><i class="fa-solid fa-bars"></i></button>

    <div class="dropdown" id="dropdown">
        <div class="user">
            <img src="http://localhost/smarthr/public/assets/defaultuser.png" alt="USERLOGO">
            <div class="info">
                <p><?php echo $fullname ?></p>
                <span style="font-weight:600">(<?php echo $_SESSION['role'] ?>)</span>
            </div>
        </div>
        <div class="contents" id="contents">
            <a href="http://localhost/smarthr/logout.php">Logout</a>
        </div>
    </div>
</div>
<div class="sidebar" id="sidebar">
    <a href="http://localhost/smarthr/emp/dashboard.php" id="logoName">
        <img src="http://localhost/smarthr/public/assets/wdLogo.jpg" alt="">
    </a>
    <nav>
        <ul>
            <li>
                <a href="http://localhost/smarthr/emp/dashboard.php">
                    <i class="fa-solid fa-house"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/smarthr/emp/application.php">
                    <i class="fa-regular fa-file-lines"></i>
                    <span>Application</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/smarthr/emp/schedules.php">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Schedules</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/smarthr/emp/candidates.php">
                    <i class="fa-solid fa-user-tag"></i>
                    <span>Candidates</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/smarthr/emp/reports.php">
                    <i class="fa-solid fa-flag"></i>
                    <span>Reports</span>
                </a>
            </li>
            <?php if ($_SESSION['role'] !== "Staff"): ?>
                <li><a href="http://localhost/smarthr/emp/accounts.php"><i class="fa-solid fa-users-gear"></i><span>Account</span></a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>