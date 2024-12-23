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
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/accounts.css">
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="accounts">
                <div class="grid">
                    <?php require "handlers/count_all.php" ?>
                    <div class="item">
                        <p>TOTAL APPLICANTS ACCOUNT</p>
                        <span><?php echo $total_applicant ?></span>
                        <a href="manage_applicant.php">View All</a>
                    </div>
                    <div class="item">
                        <p>TOTAL STAFFS ACCOUNT</p>
                        <span><?php echo $total_staff ?></span>
                        <a href="manage_staff.php">View All</a>
                    </div>
                    <div class="item">
                        <p>TOTAL ADMIN ACCOUNT</p>
                        <span><?php echo $total_admin ?></span>
                        <a href="manage_admin.php">View All</a>
                    </div>
                    <div class="item">
                        <p>TOTAL SYSTEM ACCOUNT</p>
                        <span><?php echo $total_users ?></span>
                    </div>
                </div>
                <div class="body">
                    <div class="label">
                        <h4>Newly Members</h4>
                    </div>
                    <?php

                    $all_acc = $conn->prepare("SELECT * FROM applicants ORDER BY created_at DESC LIMIT 15");
                    $all_acc->execute();
                    $acc_result = $all_acc->get_result();

                    $count = 1;
                    $accounts = [];
                    while ($acc_row = $acc_result->fetch_assoc()) {
                        $accounts[] = $acc_row;
                    }
                    ?>
                    <div class="table">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Firstname</th>
                                    <th>Middlename</th>
                                    <th>Lastname</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Joined</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($accounts)): ?>
                                    <?php foreach ($accounts as $account): ?>
                                        <tr>
                                            <td><?php echo $count++ ?></td>
                                            <td><?php echo htmlspecialchars($account['firstname']) ?></td>
                                            <td><?php echo htmlspecialchars($account['middlename']) ?></td>
                                            <td><?php echo htmlspecialchars($account['lastname']) ?></td>
                                            <td><?php echo htmlspecialchars($account['age']) ?></td>
                                            <td><?php echo htmlspecialchars($account['gender']) ?></td>
                                            <td><?php echo htmlspecialchars($account['phonenumber']) ?></td>
                                            <td><?php echo htmlspecialchars($account['address']) ?></td>
                                            <td><?php echo htmlspecialchars($account['email']) ?></td>
                                            <td><?php echo htmlspecialchars($account['created_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
</body>

</html>