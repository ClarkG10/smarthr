<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and get POST data
    $candidate_applied_id = $_POST['candidate_applied_id'];
    $status = "Awaiting";

    $check_can = $conn->prepare("SELECT * FROM candidates WHERE candidate_applied_id = $candidate_applied_id");
    $check_can->execute();
    $check_result = $check_can->get_result();

    if ($check_result->num_rows === 0) {
        $insert_sql = $conn->prepare("INSERT INTO `candidates`(`candidate_applied_id`, `candidate_status`, `added_date`) 
    VALUES (?, ?, NOW())");
        $insert_sql->bind_param("is", $candidate_applied_id, $status);
        if ($insert_sql->execute()) {
            echo '<script> alert("Successfully added as Candidate"); location.href = "../../schedules.php"; </script>';
            exit();
        }
    } else {
        echo '<script> alert("Already added as Candidate"); location.href = "../../schedules.php"; </script>';
        exit();
    }
}
