<?php

require "../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $cancel_applied_id = $_POST['cancel_applied_id'];

    $delete_sql = $conn->prepare("DELETE FROM job_applicants WHERE applied_id = $cancel_applied_id");
    if ($delete_sql->execute()) {
        header("Location: http://localhost/smarthr/applicant/applications.php");
        exit();
    }
}
