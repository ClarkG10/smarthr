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
    <link rel="stylesheet" href="http://localhost/smarthr/emp/css/add/acc.css">

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
                        <h4>ALL STAFFS ACCOUNT</h4>
                        <button onclick="addStaffModal()">ADD ACCOUNT</button>
                    </div>
                    <?php
                    $all_acc = $conn->prepare("SELECT * FROM staffs");
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
                                                    <button style="background-color:blue" onclick="showUpdateModal(<?php echo htmlspecialchars($account['staff_id']) ?>)">UPDATE</button>
                                                    <form action="handlers/account/delete.account.php" method="POST">
                                                        <input type="hidden" name="delete_staff" value="<?php echo htmlspecialchars($account['staff_id']) ?>">
                                                        <button style="background-color:red" type="submit" name="deleteStaffBtn">DELETE</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <div class="update-account" id="updateStaff<?php echo htmlspecialchars($account['staff_id']) ?>">
                                            <div class="update-content">
                                                <div class="update-header">
                                                    <h4>UPDATE STAFF INFORMATION</h4>
                                                </div>
                                                <div class="update-body">
                                                    <form action="handlers/account/update.account.php" method="POST">
                                                        <input type="hidden" name="staff_id" value="<?php echo htmlspecialchars($account['staff_id']); ?>">
                                                        <div class="update-row">
                                                            <div class="update-input">
                                                                <p>Firstname</p>
                                                                <input type="text" name="update_firstname" value="<?php echo htmlspecialchars($account['firstname']); ?>">
                                                            </div>
                                                            <div class="update-input">
                                                                <p>Middlename</p>
                                                                <input type="text" name="update_middlename" value="<?php echo htmlspecialchars($account['middlename']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="update-row">
                                                            <div class="update-input">
                                                                <p>Lastname</p>
                                                                <input type="text" name="update_lastname" value="<?php echo htmlspecialchars($account['lastname']); ?>">
                                                            </div>
                                                            <div class="update-input">
                                                                <p>Age</p>
                                                                <input type="number" name="update_age" value="<?php echo htmlspecialchars($account['age']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="update-row">
                                                            <div class="update-input">
                                                                <p>Gender</p>
                                                                <input type="text" name="update_gender" value="<?php echo htmlspecialchars($account['gender']); ?>">
                                                            </div>
                                                            <div class="update-input">
                                                                <p>Phone Number</p>
                                                                <input type="text" name="update_phone" value="<?php echo htmlspecialchars($account['phonenumber']); ?>">
                                                            </div>
                                                        </div>
                                                        <div class="update-input">
                                                            <p>Address</p>
                                                            <input type="text" name="update_address" value="<?php echo htmlspecialchars($account['address']); ?>">
                                                        </div>
                                                        <div class="update-input">
                                                            <p>Email</p>
                                                            <input type="email" name="update_email" value="<?php echo htmlspecialchars($account['email']); ?>">
                                                        </div>
                                                        <div class="update-input">
                                                            <p>Update Password</p>
                                                            <input type="password" name="update_password" value="">
                                                        </div>
                                                        <div class="update-input-btn">
                                                            <input type="submit" name="submitUpdateBtn" value="UPDATE" style="background-color:blue">
                                                            <button style="background-color:red" type="button" onclick="closeUpdateModal(<?php echo htmlspecialchars($account['staff_id']); ?>)">CANCEL</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10">No accounts found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="add-account" id="addStaff">
                        <div class="update-content">
                            <div class="update-header">
                                <h4>CREATE STAFF INFORMATION</h4>
                            </div>
                            <div class="update-body">
                                <form action="handlers/account/add.account.php" method="POST" class="addFormStaff">
                                    <div class="update-row">
                                        <div class="update-input">
                                            <p>Firstname</p>
                                            <input type="text" name="firstname" value="">
                                        </div>
                                        <div class="update-input">
                                            <p>Middlename</p>
                                            <input type="text" name="middlename" value="">
                                        </div>
                                    </div>
                                    <div class="update-row">
                                        <div class="update-input">
                                            <p>Lastname</p>
                                            <input type="text" name="lastname" value="">
                                        </div>
                                        <div class="update-input">
                                            <p>Age</p>
                                            <input type="number" name="age" value="">
                                        </div>
                                    </div>
                                    <div class="update-row">
                                        <div class="update-input">
                                            <p>Gender</p>
                                            <select name="gender" style="font-size: 12px; padding: 5px">
                                                <option value="" selected disabled>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="update-input">
                                            <p>Phone Number</p>
                                            <input type="text" name="phone" value="">
                                        </div>
                                    </div>
                                    <div class="update-input">
                                        <p>Address</p>
                                        <input type="text" name="address" value="">
                                    </div>
                                    <div class="update-input">
                                        <p>Email</p>
                                        <input type="email" name="email" value="">
                                    </div>
                                    <div class="update-input">
                                        <p>Password</p>
                                        <input type="password" name="password" value="">
                                    </div>
                                    <div class="update-input-btn">
                                        <input type="submit" name="submitAddStaff" value="CREATE" style="background-color:blue">
                                        <button style="background-color:red" type="button" onclick="closeStaffModal()">CANCEL</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="http://localhost/smarthr/emp/js/navigation.js"></script>
    <script>
        function addStaffModal() {
            document.getElementById("addStaff").style.display = "flex";
        }

        function closeStaffModal() {
            document.getElementById("addStaff").style.display = "none";
        }

        function showUpdateModal(staffId) {
            document.getElementById("updateStaff" + staffId).style.display = "flex";
        }

        function closeUpdateModal(staffId) {
            document.getElementById("updateStaff" + staffId).style.display = "none";
        }
    </script>
</body>

</html>