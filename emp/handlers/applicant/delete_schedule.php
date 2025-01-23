<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../smarthr/vendor/autoload.php';
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $delete_schedule_id = $_POST['delete_schedule_id'];
    $applied_id = $_POST['applied_id'];

    $conn->begin_transaction();

    $applicant_query = $conn->prepare("
           SELECT 
               a.firstname, 
               a.middlename, 
               a.lastname, 
               a.email,
               j.applied_job_id,
               jb.job_position
           FROM applicants a
           JOIN job_applicants j ON a.applicant_id = j.applied_applicant_id
           JOIN jobs jb ON j.applied_job_id = jb.job_id
           WHERE j.Applied_id = ?
       ");
    $applicant_query->bind_param("i", $applied_id);
    $applicant_query->execute();
    $applicant_result = $applicant_query->get_result();
    $applicant = $applicant_result->fetch_assoc();

    $delete_schedules_sql = $conn->prepare("DELETE FROM schedules WHERE schedule_id = ?");
    $delete_schedules_sql->bind_param("i", $delete_schedule_id);
    $delete_schedules_sql->execute();

    $update_sql = $conn->prepare("UPDATE job_applicants SET sched_status = 'No Schedule' WHERE applied_id = ?");
    $update_sql->bind_param("i", $applied_id);
    $update_sql->execute();

    $delete_candidate = $conn->prepare("DELETE FROM candidates WHERE candidate_applied_id = ?");
    $delete_candidate->bind_param("i", $applied_id);
    $delete_candidate->execute();

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
        // $mail->addAddress($applicant['email']);
        $mail->addAddress('dalautaclarkraven@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = 'Interview Schedule Cancellation';
        $mail->Body = sprintf(
            "Dear %s %s %s,<br><br>" .
                "We regret to inform you that your interview schedule for the position of " .
                "<strong>%s</strong> has been cancelled.<br><br>" .
                "Our HR team will be in touch with further updates about your application.",
            $applicant['firstname'],
            $applicant['middlename'],
            $applicant['lastname'],
            $applicant['job_position']
        );

        $mail->send();

        // Commit transaction
        $conn->commit();

        echo '<script>alert("Successfully Deleted Schedule and Sent Notification"); location.href = "../../schedules.php";</script>';
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo '<script>alert("Invalid Email, Failed to send an email but deleted schedule successfully"); location.href = "../../schedules.php";</script>';
        exit();
    }
}
