<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sched_date = $_POST['sched_date'];
    $sched_time = $_POST['sched_time'];
    $sched_period = $_POST['sched_period'];
    $applied_id = $_POST['sched_applied_id'];

    $check_sched = $conn->prepare("SELECT * FROM schedules WHERE schedule_applied_id = ?");
    $check_sched->bind_param("i", $applied_id);
    $check_sched->execute();
    $check_result = $check_sched->get_result();

    if ($check_result->num_rows === 0) {
        $conn->begin_transaction();

        $applicant_query = $conn->prepare("
                    SELECT 
                        a.firstname, 
                        a.middlename, 
                        a.lastname, 
                        a.email, 
                        j.applied_job_id,
                        jb.job_position,
                        jb.job_type,
                        jb.place
                    FROM applicants a
                    JOIN job_applicants j ON a.applicant_id = j.applied_applicant_id JOIN jobs jb ON j.applied_job_id = jb.job_id
                    WHERE j.Applied_id = ?
                ");
        $applicant_query->bind_param("i", $applied_id);
        $applicant_query->execute();
        $applicant_result = $applicant_query->get_result();
        $applicant = $applicant_result->fetch_assoc();

        $insert_sched = $conn->prepare("INSERT INTO schedules (schedule_applied_id, schedule_date, schedule_time, schedule_period) VALUES (?, ?, ?, ?)");
        $insert_sched->bind_param("isss", $applied_id, $sched_date, $sched_time, $sched_period);
        $insert_sched->execute();

        $update_status = $conn->prepare("UPDATE job_applicants SET applied_status = 'Scheduled' WHERE Applied_id = ?");
        $update_status->bind_param("i", $applied_id);
        $update_status->execute();

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
            $mail->addAddress($applicant['email']);
            $mail->isHTML(true);
            $mail->Subject = 'Schedule for Interview';
            $mail->Body = sprintf(
                "Dear %s %s %s,<br><br>" .
                    "We would like to inform you that your interview has been scheduled on date <strong>%s</strong> and time <strong>%s %s</strong> <br>for the position of <strong>%s</strong> and a <strong>%s</strong> job.<br><br>" .
                    "Location Address:<br><strong>%s</strong><br><br>" .
                    "We look forward to meeting you.",
                $applicant['firstname'],
                $applicant['middlename'],
                $applicant['lastname'],
                $sched_date,
                $sched_time,
                $sched_period,
                $applicant['job_position'],
                $applicant['job_type'],
                $applicant['place'],
            );

            $mail->send();
            $conn->commit();

            echo '<script>alert("Schedule Added and Notification Sent"); location.href = "../../application.php";</script>';
            exit();
        } catch (Exception $e) {
            $conn->rollback();
            echo '<script>alert("Invalid Email, Use Valid Email but Schedule been added successfully."); location.href = "../../application.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("Schedule Already Exists"); location.href = "../../application.php";</script>';
        exit();
    }
}
