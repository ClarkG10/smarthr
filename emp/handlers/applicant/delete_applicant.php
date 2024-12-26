<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $delete_applied_id = $_POST['delete_applied_id'];

    // Delete related records in applicant_scores table first
    $delete_scores_sql = $conn->prepare("DELETE FROM applicant_scores WHERE applied_id = ?");
    $delete_scores_sql->bind_param("i", $delete_applied_id);
    $delete_scores_sql->execute();

    // Delete related record in schedules table
    $delete_schedules_sql = $conn->prepare("DELETE FROM schedules WHERE schedule_applied_id = ?");
    $delete_schedules_sql->bind_param("i", $delete_applied_id);
    $delete_schedules_sql->execute();

    // Delete from job_applicants table
    $delete_sql = $conn->prepare("DELETE FROM job_applicants WHERE applied_id = ?");
    $delete_sql->bind_param("i", $delete_applied_id);

    if ($delete_sql->execute()) {
        echo '<script> alert("Successfully Deleted applicant."); location.href = "../../application.php"; </script>';
        exit();
    } else {
        echo '<script> alert("Error deleting applicant."); location.href = "../../application.php"; </script>';
        exit();
    }
}
