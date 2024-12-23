<?php

require "../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (strtolower($role) === "staff") {
        $check_email = $conn->prepare("SELECT staff_id, password, 'Staff' as role_type FROM staffs WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_result = $check_email->get_result();

        if ($check_result->num_rows === 1) {
            $data = $check_result->fetch_assoc();
            if (password_verify($password, $data['password'])) {
                session_start();

                $_SESSION['user_id'] = $data['staff_id'];
                $_SESSION['role'] = $data['role_type'];

                header("Location: ../dashboard.php");
                exit();
            }
        }
    } else {
        $check_email = $conn->prepare("SELECT admin_id, password, 'Admin' as role_type FROM admins WHERE email = ?");
        $check_email->bind_param("s", $email);
        $check_email->execute();
        $check_result = $check_email->get_result();

        if ($check_result->num_rows === 1) {
            $data = $check_result->fetch_assoc();
            if (password_verify($password, $data['password'])) {
                session_start();

                $_SESSION['user_id'] = $data['admin_id'];
                $_SESSION['role'] = $data['role_type'];

                header("Location: ../dashboard.php");
                exit();
            }
        }
    }
}
