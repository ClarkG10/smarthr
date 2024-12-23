<?php

require "database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phonenumber = $_POST['phonenumber'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $check_email = $conn->prepare("SELECT * FROM `applicants` WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_result = $check_email->get_result();

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if ($check_result->num_rows === 0) {
        $insert_sql = $conn->prepare("INSERT INTO `applicants`(`firstname`, `middlename`, `lastname`, `age`, `gender`, `phonenumber`, `address`, `email`, `password`, `created_at`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        $insert_sql->bind_param("sssisssss", $firstname, $middlename, $lastname, $age, $gender, $phonenumber, $address, $email, $password_hash);
        if ($insert_sql->execute()) {
            echo '<script>alert("Successfully Created."); window.location.href = "http://localhost/smarthr/login.php"</script>';
            exit();
        }
    } else {
        echo '<script>alert("Email is taken already."); window.location.href = "http://localhost/smarthr/register.php"</script>';
        exit();
    }
}
