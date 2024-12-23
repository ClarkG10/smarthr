<?php
// Prepare the SQL statement with a placeholder
$user_data = $conn->prepare("SELECT * FROM applicants WHERE applicant_id = ?");
$user_data->bind_param("i", $userId);
$user_data->execute();
$user_result = $user_data->get_result();

if ($user_result->num_rows > 0) {
    $userInfo = $user_result->fetch_assoc();
    $fullname = $userInfo['firstname'] . " " . $userInfo['lastname'];
}
