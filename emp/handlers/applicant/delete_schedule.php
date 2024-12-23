<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $delete_schedule_id = $_POST['delete_schedule_id'];
    $applied_id = $_POST['applied_id'];

    $delete_schedules_sql = $conn->prepare("DELETE FROM schedules WHERE schedule_id = ?");
    $delete_schedules_sql->bind_param("i", $delete_schedule_id);
    if ($delete_schedules_sql->execute()) {
        $update_sql = $conn->prepare("UPDATE job_applicants SET sched_status = 'No Schedule' WHERE applied_id = $applied_id");
        $update_sql->execute();
    }

    $delete_candidate = $conn->prepare("DELETE FROM candidates WHERE candidate_applied_id = $applied_id");
    $delete_candidate->execute();

    echo '<script> alert("Successfully Delete Scheduled."); location.href = "../../schedules.php"; </script>';
    exit();
}
