<?php
require "../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $cancel_applied_id = $_POST['cancel_applied_id'];

    // First, delete the related scores from applicant_scores
    $delete_scores_sql = $conn->prepare("DELETE FROM applicant_scores WHERE applied_id = ?");
    $delete_scores_sql->bind_param("i", $cancel_applied_id);

    if ($delete_scores_sql->execute()) {
        // then delete the row from job_applicants
        $delete_applicant_sql = $conn->prepare("DELETE FROM job_applicants WHERE applied_id = ?");
        $delete_applicant_sql->bind_param("i", $cancel_applied_id);

        if ($delete_applicant_sql->execute()) {
            header("Location: http://localhost/smarthr/applicant/applications.php");
            exit();
        } else {
            echo "Error deleting from job_applicants: " . $delete_applicant_sql->error;
        }
    } else {
        echo "Error deleting from applicant_scores: " . $delete_scores_sql->error;
    }
}
