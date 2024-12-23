<?php

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if (strtolower($role) === 'staff') {
    $user_data = $conn->prepare("SELECT * FROM staffs WHERE staff_id = ?");
    $user_data->bind_param("i", $userId);
    $user_data->execute();
    $user_result = $user_data->get_result();
} else {
    $user_data = $conn->prepare("SELECT * FROM admins WHERE admin_id = ?");
    $user_data->bind_param("i", $userId);
    $user_data->execute();
    $user_result = $user_data->get_result();
}

if ($user_result->num_rows > 0) {
    $userInfo = $user_result->fetch_assoc();
    $fullname = $userInfo['firstname'] . " " . $userInfo['lastname'];
}
