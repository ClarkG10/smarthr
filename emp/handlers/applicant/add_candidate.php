<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $candidate_applied_id = $_POST['candidate_applied_id'];
    $status = "Awaiting";

    $applicant_query = $conn->prepare("
        SELECT 
            a.firstname, 
            a.middlename, 
            a.lastname, 
            a.email,
            j.applied_job_id,
            jb.job_position,
            jb.job_type
        FROM applicants a
        JOIN job_applicants j ON a.applicant_id = j.applied_applicant_id
        JOIN jobs jb ON j.applied_job_id = jb.job_id 
        WHERE j.Applied_id = ?
    ");
    $applicant_query->bind_param("i", $candidate_applied_id);
    $applicant_query->execute();
    $applicant_result = $applicant_query->get_result();
    $applicant = $applicant_result->fetch_assoc();

    $conn->begin_transaction();

    $check_can = $conn->prepare("SELECT * FROM candidates WHERE candidate_applied_id = ?");
    $check_can->bind_param("i", $candidate_applied_id);
    $check_can->execute();
    $check_result = $check_can->get_result();

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'clarkravendalauta2@gmail.com';
        $mail->Password   = 'iymgciyabganijob';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('hr@waterdistrict.com', 'Water District HR Department');
        $mail->addAddress('dalautaclarkraven@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Candidate Status Update';
        $mail->Body = sprintf(
            "Dear %s %s %s,<br><br>" .
                "We are excited to inform you that you have been added to our candidate pool " .
                "for the <strong>%s</strong> position. Your application for a <strong>%s</strong> job " .
                "is currently in the 'Awaiting' status.<br><br>" .
                "Our hiring team will review your application and reach out with you or you will recieve an email. " .
                "Thank you for your interest in joining our team.",
            $applicant['firstname'],
            $applicant['middlename'],
            $applicant['lastname'],
            $applicant['job_position'],
            $applicant['job_type']
        );

        if ($check_result->num_rows === 0) {
            $insert_sql = $conn->prepare("INSERT INTO `candidates`(`candidate_applied_id`, `candidate_status`, `added_date`) VALUES (?, ?, NOW())");
            $insert_sql->bind_param("is", $candidate_applied_id, $status);
            $insert_sql->execute();

            $mail->send();

            $conn->commit();

            echo '<script>alert("Successfully Added as Candidate and Notification Sent"); location.href = "../../schedules.php";</script>';
            exit();
        } else {
            echo '<script>alert("Already Added as Candidate"); location.href = "../../schedules.php";</script>';
            exit();
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo '<script>alert("Failed to send an Email but canditate has been created"); location.href = "../../schedules.php";</script>';
        exit();
    }
}
