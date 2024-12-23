<?php

require "database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check_email = $conn->prepare("SELECT applicant_id, password, 'Applicant' as role_type FROM applicants WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_result = $check_email->get_result();

    if ($check_result->num_rows === 1) {
        $user = $check_result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();

            $_SESSION['user_id'] = $user['applicant_id'];
            $_SESSION['role'] = $user['role_type'];

            header("Location: http://localhost/smarthr/applicant/opportunities.php");
            exit();
        } else {
            echo '<script>alert("Invalid Password."); window.location.href = "http://localhost/smarthr/login.php"</script>';
        }
    } else {
        echo '<script>alert("Invalid User."); window.location.href = "http://localhost/smarthr/login.php"</script>';
    }
}
