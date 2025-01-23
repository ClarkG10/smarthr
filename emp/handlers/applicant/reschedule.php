<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $schedule_date = $_POST['schedule_date'];
    $schedule_time = $_POST['schedule_time'];
    $schedule_period = $_POST['schedule_period'];
    $schedule_id = $_POST['schedule_id'];

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
         JOIN schedules s ON s.schedule_applied_id = j.applied_id 
         JOIN jobs jb ON j.applied_job_id = jb.job_id
         WHERE s.schedule_id = ?
     ");
    $applicant_query->bind_param("i", $schedule_id);
    $applicant_query->execute();
    $applicant_result = $applicant_query->get_result();
    $applicant = $applicant_result->fetch_assoc();

    $update_sql = $conn->prepare("UPDATE `schedules` SET `schedule_date`= ?,`schedule_time`= ?, `schedule_period` = ? WHERE `schedule_id` = ?");
    $update_sql->bind_param("sssi", $schedule_date, $schedule_time, $schedule_period, $schedule_id);
    $update_sql->execute();

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
        $mail->Subject = 'Rescheduled Interview';
        $mail->Body = sprintf(
            "Dear %s %s %s,<br><br>" .
                "Your interview schedule has been updated for the position of <strong>%s</strong>.<br><br>" .
                "New Interview Details:<br>" .
                "Date: <strong>%s</strong><br>" .
                "Time: <strong>%s %s</strong><br><br>" .
                "Please confirm your availability for this new schedule.",
            $applicant['firstname'],
            $applicant['middlename'],
            $applicant['lastname'],
            $applicant['job_position'],
            $schedule_date,
            $schedule_time,
            $schedule_period
        );

        $mail->send();

        // Commit transaction
        $conn->commit();

        echo '<script>alert("Successfully Re-Scheduled and Notification Sent."); location.href = "../../schedules.php";</script>';
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo '<script>alert("Failed to send and Email but Rescheduled Successfully."); location.href = "../../schedules.php";</script>';
        exit();
    }
}
