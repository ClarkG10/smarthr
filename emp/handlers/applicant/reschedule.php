<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and get POST data
    $schedule_date = $_POST['schedule_date'];
    $schedule_time = $_POST['schedule_time'];
    $schedule_id = $_POST['schedule_id'];

    $update_sql = $conn->prepare("UPDATE `schedules` SET `schedule_date`= ?,`schedule_time`= ? WHERE `schedule_id` = ?");
    $update_sql->bind_param("ssi", $schedule_date, $schedule_time, $schedule_id);
    if ($update_sql->execute()) {
        echo '<script> alert("Successfully Re-Scheduled."); location.href = "../../schedules.php"; </script>';
                exit();
    } else {
        echo "Error: " . $update_sql->error;
    }
}
