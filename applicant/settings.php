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
    <link rel="stylesheet" href="http://localhost/smarthr/applicant/css/settings.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="setting">
                <div class="wrapper">
                    <div class="label">
                        <h4>ACCOUNT SETTINGS</h4>
                    </div>
                    <form action="" method="POST">
                        <div class="setting-row">
                            <div class="setting-input" id="profile">
                                <img src="http://localhost/smarthr/public/assets/defaultuser.png" alt="">
                                <input type="file">
                            </div>
                            <div id="names">
                                <div class="setting-input">
                                    <p>First Name</p>
                                    <input type="text" name="update_firstname" value="<?php echo htmlspecialchars($userInfo['firstname']) ?>" required autocomplete="off">
                                </div>
                                <div class="setting-input">
                                    <p>Middle Name</p>
                                    <input type="text" name="update_middlename" value="<?php echo htmlspecialchars($userInfo['middlename']) ?>" required autocomplete="off">
                                </div>
                                <div class="setting-input">
                                    <p>Last Name</p>
                                    <input type="text" name="update_lastname" value="<?php echo htmlspecialchars($userInfo['lastname']) ?>" required autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="set3">
                            <div class="setting-input">
                                <p>Age</p>
                                <input type="text" name="update_age" value="<?php echo htmlspecialchars($userInfo['age']) ?>" required autocomplete="off">
                            </div>
                            <div class="setting-input">
                                <p>Gender</p>
                                <select name="update_gender" id="update_gender" required>
                                    <option value="<?php echo htmlspecialchars($userInfo['gender']) ?>"><?php echo htmlspecialchars($userInfo['gender']) ?></option>
                                    <?php if ($userInfo['gender'] === "Male"): ?>
                                        <option value="Female">Female</option>
                                    <?php else: ?>
                                        <option value="Male">Male</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="setting-input">
                                <p>Phone Number</p>
                                <input type="text" name="update_phonenumber" value="<?php echo htmlspecialchars($userInfo['phonenumber']) ?>" required autocomplete="off">
                            </div>
                        </div>
                        <div class="setting-input">
                            <p>Address</p>
                            <input type="text" name="update_address" value="<?php echo htmlspecialchars($userInfo['address']) ?>" required autocomplete="off">
                        </div>
                        <div class="setting-input">
                            <p>Email</p>
                            <input type="email" name="update_email" value="<?php echo htmlspecialchars($userInfo['email']) ?>" required autocomplete="off">
                        </div>
                        <div class="setting-input">
                            <p>Password</p>
                            <input type="password" name="update_password" placeholder="UPDATE PASSWORD" autocomplete="off">
                        </div>
                        <div class="setting-button">
                            <input type="submit" name="submit_update" value="UPDATE">
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
</body>

</html>