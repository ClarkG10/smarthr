<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD']) {
    $sched_date = $_POST['sched_date'];
    $sched_time = $_POST['sched_time'];
    $applied_id = $_POST['sched_applied_id'];

    $check_sched = $conn->prepare("SELECT * FROM schedules WHERE schedule_applied_id = $applied_id");
    $check_sched->execute();
    $check_result = $check_sched->get_result();

    if ($check_result->num_rows === 0) {
        $insert_sched = $conn->prepare("INSERT INTO `schedules`(`schedule_applied_id`, `schedule_date`, `schedule_time`) 
        VALUES (?, ?, ?)");
        $insert_sched->bind_param("iss", $applied_id, $sched_date, $sched_time);
        if ($insert_sched->execute()) {
            $update_status = $conn->prepare("UPDATE `job_applicants` SET `sched_status` = 'Scheduled' WHERE `Applied_id`= $applied_id");
            if ($update_status->execute()) {
                echo '<script> alert("Successfully Add Scheduled."); location.href = "../../application.php"; </script>';
                exit();
            }
        }
    } else {
        echo '<script> alert("This applicant already has a schedule."); location.href = "../../application.php"; </script>';
        exit();
    }
} else {
    echo '<script> alert("Request Method Error."); location.href = "../../application.php"; </script>';
    exit();
}
