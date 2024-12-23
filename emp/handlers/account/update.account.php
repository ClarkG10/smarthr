<?php
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    if (isset($_POST['submitUpdateBtn'])) {
        $staff_id = $_POST['staff_id'];
        $firstname = $_POST['update_firstname'];
        $middlename = $_POST['update_middlename'];
        $lastname = $_POST['update_lastname'];
        $age = $_POST['update_age'];
        $gender = $_POST['update_gender'];
        $phone = $_POST['update_phone'];
        $address = $_POST['update_address'];
        $email = $_POST['update_email'];
        $password = $_POST['update_password'];

        // Check if the email is already taken
        $check_sql = $conn->prepare("SELECT * FROM staffs WHERE email = ? AND staff_id != ?");
        $check_sql->bind_param("si", $email, $staff_id);
        $check_sql->execute();
        $result = $check_sql->get_result();

        if ($result->num_rows > 0) {
            echo '<script> alert("Email is already taken. Please choose another email."); location.href = "../../manage_staff.php"; </script>';
            exit();
        }

        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = $conn->prepare("UPDATE staffs SET firstname = ?, middlename = ?, lastname = ?, age = ?, gender = ?, phonenumber = ?, address = ?, email = ?, password = ? WHERE staff_id = ?");
            $update_sql->bind_param("sssssssssi", $firstname, $middlename, $lastname, $age, $gender, $phone, $address, $email, $hashed_password, $staff_id);
        } else {
            $update_sql = $conn->prepare("UPDATE staffs SET firstname = ?, middlename = ?, lastname = ?, age = ?, gender = ?, phonenumber = ?, address = ?, email = ? WHERE staff_id = ?");
            $update_sql->bind_param("ssssssssi", $firstname, $middlename, $lastname, $age, $gender, $phone, $address, $email, $staff_id);
        }

        if ($update_sql->execute()) {
            echo '<script> alert("Successfully Updated."); location.href = "../../manage_staff.php"; </script>';
            exit();
        } else {
            echo '<script> alert("Update Failed. Please try again."); location.href = "../../manage_staff.php"; </script>';
            exit();
        }
    }
} else {
    echo '<script> alert("Request Method Error."); location.href = "../../manage_staff.php"; </script>';
    exit();
}
