<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $decline_candidate_id = $_POST['decline_candidate_id'];
    $remarks = $_POST['remarks'];

    $checked = $conn->prepare("SELECT * FROM candidates WHERE candidate_id = $decline_candidate_id");
    $checked->execute();
    $result = $checked->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        if ($data['candidate_status'] !== 'Decline') {
            $approved = $conn->prepare("UPDATE candidates SET candidate_status = 'Declined', remarks = '$remarks'  WHERE candidate_id = $decline_candidate_id");
            $approved->execute();

            echo '<script> alert("Successfully Declined"); location.href = "../../candidates.php"; </script>';
            exit();
        } else {
            echo '<script> alert("Already Approved/Declined."); location.href = "../../candidates.php"; </script>';
            exit();
        }
    }
}
