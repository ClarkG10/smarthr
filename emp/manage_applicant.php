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
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/manage.css">

    <style>
        .acc-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }

        .acc-buttons button {
            border: none;
            outline: none;
            padding: 5px 7px;
            color: white;
            border-radius: 5px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <?php include "includes/navigation.php" ?>
    <main>
        <section>
            <div class="manage">
                <div class="wrap">
                    <div class="label">
                        <h4>ALL APPLICANTS ACCOUNT</h4>
                        <button>ADD ACCOUNT</button>
                    </div>
                    <?php

                    $all_acc = $conn->prepare("SELECT * FROM applicants");
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
                                    <th>Action</th>
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
                                            <td>
                                                <div class="acc-buttons">
                                                    <!-- <button style="background-color:blue">UPDATE</button> -->
                                                    <form action="handlers/account/delete.account.php" method="POST">
                                                        <input type="hidden" name="delete_applicant" value="<?php echo htmlspecialchars($account['applicant_id']) ?>">
                                                        <button style="background-color:red" type="submit" name="deleteApplicantBtn">DELETE</button>
                                                    </form>
                                                </div>
                                            </td>
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