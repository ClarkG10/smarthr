<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../../vendor/autoload.php';
require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $approve_candidate_id = $_POST['approve_candidate_id'];
    $remarks = $_POST['remarks'];

    $conn->begin_transaction();
    $checked = $conn->prepare("
    SELECT 
        a.firstname, 
        a.middlename, 
        a.lastname, 
        a.email, 
        c.candidate_status, 
        j.applied_ratings,
        jb.job_position,
        jb.job_type
     FROM applicants a
    JOIN job_applicants j ON a.applicant_id = j.applied_applicant_id 
    JOIN jobs jb ON j.applied_job_id = jb.job_id 
    JOIN candidates c ON j.applied_id = c.candidate_applied_id
    WHERE c.candidate_id = ?
");


    $checked->bind_param("i", $approve_candidate_id);
    $checked->execute();
    $result = $checked->get_result();

    if ($result->num_rows === 1) {
        $data = $result->fetch_assoc();

        if ($data['candidate_status'] !== 'Approved') {
            $approved = $conn->prepare("
                UPDATE candidates 
                SET candidate_status = 'Approved', remarks = ? 
                WHERE candidate_id = ?
            ");
            $approved->bind_param("si", $remarks, $approve_candidate_id);
            $approved->execute();

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
                $mail->addAddress($data['email']);
                $mail->isHTML(true);
                $mail->Subject = 'Exciting News: Your Application Has Been Approved!';
                $mail->Body = sprintf(
                    "Dear %s %s %s,<br><br>" .
                        "We are thrilled to inform you that you have been approved as a new hire for the position of %s and for our team with ratings of <strong>%d</strong>. Your dedication and qualifications have truly stood out, and we are excited to welcome you on this journey as a %s employee.<br><br>" .
                        "Here are some important details to note:<br>" .
                        "<ul>" .
                        "<li><strong>Remarks:</strong> %s</li>" .
                        "</ul><br>" .
                        "We value the energy and enthusiasm that you bring, and we believe that your skills will make a meaningful impact. Our team is committed to supporting you every step of the way.<br><br>" .
                        "If you have any questions or need assistance, please do not hesitate to reach out to us. You can reply directly to this email, and we will get back to you promptly.<br><br>" .
                        "Thank you for being part of this process. We look forward to seeing your future contributions and achievements.<br><br>" .
                        "Warm regards,<br>" .
                        "<strong>Water District HR Department</strong><br>" .
                        "Email: hr@waterdistrict.com<br>" .
                        "Phone: 09123456789",
                    $data['firstname'],
                    $data['middlename'],
                    $data['lastname'],
                    $data['job_position'],
                    $data['applied_ratings'],
                    $data['job_type'],
                    $remarks
                );

                $mail->send();

                $conn->commit();

                echo '<script>alert("Successfully Approved and Notification Sent."); location.href = "../../candidates.php";</script>';
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                echo '<script>alert("Failed to send email but candidate was approved successfully."); location.href = "../../candidates.php";</script>';
                exit();
            }
        } else {
            echo '<script>alert("Candidate is already Approved/Declined."); location.href = "../../candidates.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("Candidate not found."); location.href = "../../candidates.php";</script>';
        exit();
    }
}
