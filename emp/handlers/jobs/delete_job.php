<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $delete_job_id = $_POST['delete_job_id'];

    $delete_sql = $conn->prepare("DELETE FROM jobs WHERE `job_id` = $delete_job_id");
    if ($delete_sql->execute()) {
        echo '<script> alert("Successfully Deleted Job."); location.href = "../../manage_jobs.php"; </script>';
        exit();
    }
}
