<?php
$newApp = $conn->prepare("SELECT c.*, ja.*, a.*, j.* FROM candidates c JOIN job_applicants ja ON c.candidate_applied_id = ja.applied_id JOIN applicants a ON ja.applied_applicant_id = a.applicant_id 
                            JOIN jobs j ON ja.applied_job_id = j.job_id WHERE c.candidate_status <> 'Awaiting' AND c.candidate_status <> 'Declined' ORDER BY ja.applied_ratings DESC LIMIT 10");
$newApp->execute();
$new_result = $newApp->get_result();

$newCount = 1;
$newlys = [];
while ($new_rows = $new_result->fetch_assoc()) {
    $newlys[] = $new_rows;
}
