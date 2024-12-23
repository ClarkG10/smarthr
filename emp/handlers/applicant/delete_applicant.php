<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $delete_applied_id = $_POST['delete_applied_id'];

    $delete_schedules_sql = $conn->prepare("DELETE FROM schedules WHERE schedule_applied_id = ?");
    $delete_schedules_sql->bind_param("i", $delete_applied_id);
    $delete_schedules_sql->execute();

    $delete_sql = $conn->prepare("DELETE FROM job_applicants WHERE applied_id = ?");
    $delete_sql->bind_param("i", $delete_applied_id);

    if ($delete_sql->execute()) {
        echo '<script> alert("Successfully Delete applicant."); location.href = "../../application.php"; </script>';
        exit();
    } else {
        echo '<script> alert("Error deleting applicant."); location.href = "../../application.php"; </script>';
        exit();
    }
}
