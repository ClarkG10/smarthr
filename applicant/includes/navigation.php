<div class="topbar">
    <button id="toggleSidebar" onclick="toggleSidebar(event)"><i class="fa-solid fa-bars"></i></button>

    <div class="dropdown" id="dropdown">
        <div class="user">
            <img src="http://localhost/smarthr/public/assets/defaultuser.png" alt="USERLOGO">
            <div class="info">
                <p><?php echo $fullname ?></p>
                <span>(<?php echo $_SESSION['role'] ?>)</span>
            </div>
        </div>
        <div class="contents" id="contents">
            <a href="http://localhost/smarthr/applicant/settings.php">Settings</a>
            <a href="http://localhost/smarthr/logout.php">Logout</a>
        </div>
    </div>
</div>
<div class="sidebar" id="sidebar">
    <a href="" id="logoName">
        <img src="http://localhost/smarthr/public/assets/wdLogo.jpg" alt="">
    </a>
    <nav>
        <ul>
            <li>
                <a href="http://localhost/smarthr/applicant/opportunities.php">
                    <i class="fa-solid fa-briefcase"></i>
                    <span>Opporunities</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/smarthr/applicant/applications.php">
                    <i class="fa-regular fa-file-lines"></i>
                    <span>Application</span>
                </a>
            </li>
            <li>
                <a href="http://localhost/smarthr/applicant/schedules.php">
                    <i class="fa-regular fa-calendar-days"></i>
                    <span>Schedules</span>
                </a>
            </li>
        </ul>
    </nav>
</div>