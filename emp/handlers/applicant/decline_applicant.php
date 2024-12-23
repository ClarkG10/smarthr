<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $decline_applicant_id = $_POST['decline_applicant_id'];
    $remarks = $_POST['remarks'];
    $status = "Declined";
    $decline_sql = $conn->prepare("UPDATE job_applicants SET sched_status = ?, remarks = ? WHERE applied_id = ?");
    $decline_sql->bind_param("ssi", $status, $remarks, $decline_applicant_id);
    $decline_sql->execute();

    echo '<script> alert("Successfully Declined."); location.href = "../../schedules.php"; </script>';
    exit();
}
