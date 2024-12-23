<?php

session_start();

if (!isset($_SESSION['user_id']) && $_SESSION['role'] !== "Applicant") {
    header("Location: http://localhost/smarthr/login.php");
    exit();
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];
