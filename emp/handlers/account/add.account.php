<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['deleteApplicantBtn'])) {
        $delete_applicant = $_POST['delete_applicant'];

        $delete_sql = $conn->prepare("DELETE FROM applicants WHERE applicant_id = ?");
        $delete_sql->bind_param("i", $delete_applicant);
        if ($delete_sql->execute()) {
            echo '<script> alert("Successfully Deleted."); location.href = "../../manage_applicant.php"; </script>';
            exit();
        }
    }

    // Add Staff
    if (isset($_POST['submitAddStaff'])) {
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password']; // Fix typo: 'password' instead of 'passoword'

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $check_sql = $conn->prepare("SELECT * FROM staffs WHERE email = ?");
        $check_sql->bind_param("s", $email);
        $check_sql->execute();
        $result = $check_sql->get_result();

        if ($result->num_rows === 0) {
            $insert_sql = $conn->prepare("INSERT INTO `staffs`(`firstname`, `middlename`, `lastname`, `age`, `gender`, `phonenumber`, `address`, `email`, `password`, `created_at`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $insert_sql->bind_param("sssisssss", $firstname, $middlename, $lastname, $age, $gender, $phone, $address, $email, $hash);
            $insert_sql->execute();

            echo '<script> alert("Staff Successfully Created."); location.href = "../../manage_staff.php"; </script>';
            exit();
        } else {
            echo '<script> alert("Email already exists for staff."); location.href = "../../manage_staff.php"; </script>';
        }
    }

    // Add Admin
    if (isset($_POST['submitAddAdmin'])) {
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $lastname = $_POST['lastname'];
        $age = $_POST['age'];
        $gender = $_POST['gender'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $password = $_POST['password']; // Fix typo: 'password' instead of 'passoword'

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $check_sql = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $check_sql->bind_param("s", $email);
        $check_sql->execute();
        $result = $check_sql->get_result();

        if ($result->num_rows === 0) {
            $insert_sql = $conn->prepare("INSERT INTO `admins`(`firstname`, `middlename`, `lastname`, `age`, `gender`, `phonenumber`, `address`, `email`, `password`, `created_at`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
            $insert_sql->bind_param("sssisssss", $firstname, $middlename, $lastname, $age, $gender, $phone, $address, $email, $hash);
            $insert_sql->execute();

            echo '<script> alert("Admin Successfully Created."); location.href = "../../manage_admin.php"; </script>';
            exit();
        } else {
            echo '<script> alert("Email already exists for admin."); location.href = "../../manage_admin.php"; </script>';
        }
    }
} else {
    echo '<script> alert("Request Method Error."); location.href = "../../accounts.php"; </script>';
    exit();
}
