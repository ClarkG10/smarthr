<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $decline_applicant_id = $_POST['decline_applicant_id'];
    $remarks = $_POST['remarks'];
    $status = "Declined";

    $applicant_query = $conn->prepare("
        SELECT 
            a.firstname, 
            a.middlename, 
            a.lastname, 
            a.email,
            j.applied_job_id,
            jb.job_position
        FROM applicants a
        JOIN job_applicants j ON a.applicant_id = j.applied_applicant_id JOIN jobs jb ON j.applied_job_id = jb.job_id
        WHERE j.applied_id = ?
    ");
    $applicant_query->bind_param("i", $decline_applicant_id);
    $applicant_query->execute();
    $applicant_result = $applicant_query->get_result();
    $applicant = $applicant_result->fetch_assoc();

    $conn->begin_transaction();

    $decline_sql = $conn->prepare("UPDATE job_applicants SET sched_status = ?, remarks = ? WHERE applied_id = ?");
    $decline_sql->bind_param("ssi", $status, $remarks, $decline_applicant_id);
    $decline_sql->execute();

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
        $mail->addAddress($applicant['email']);
        $mail->isHTML(true);
        $mail->Subject = 'Application Status Update';
        $mail->Body = sprintf(
            "Dear %s %s %s,<br><br>" .
                "After careful consideration, we regret to inform you that your application " .
                "for the position of <strong>%s</strong> has been declined.<br><br>" .
                "Remarks: %s<br><br>" .
                "We appreciate your interest in our company and wish you the best in your future endeavors.",
            $applicant['firstname'],
            $applicant['middlename'],
            $applicant['lastname'],
            $applicant['job_position'],
            htmlspecialchars($remarks)
        );

        $mail->send();

        $conn->commit();

        echo '<script>alert("Successfully Declined and Notification Sent."); location.href = "../../schedules.php";</script>';
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo '<script>alert("Invalit Email, Use Valid Email but Application has been Succesffuly Declined"); location.href = "../../application.php";</script>';
        exit();
    }
}
