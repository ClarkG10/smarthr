<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Sanitize and get POST data
    $approve_candidate_id = $_POST['approve_candidate_id'];
    $remarks = $_POST['remarks'];

    $checked = $conn->prepare("SELECT * FROM candidates WHERE candidate_id = $approve_candidate_id");
    $checked->execute();
    $result = $checked->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        if ($data['candidate_status'] !== 'Approved') {
            $approved = $conn->prepare("UPDATE candidates SET candidate_status = 'Approved', remarks = '$remarks'  WHERE candidate_id = $approve_candidate_id");
            $approved->execute();

            echo '<script> alert("Successfully Approved"); location.href = "../../candidates.php"; </script>';
            exit();
        }else{
            echo '<script> alert("Already Approved/Declined."); location.href = "../../candidates.php"; </script>';
            exit();
        }
    }
}
