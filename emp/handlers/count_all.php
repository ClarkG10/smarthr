<?php
//COUNT APPLICANTS ACCOUNT
$count_applicants = $conn->prepare("SELECT COUNT(*) as 'total_applicant' FROM applicants");
$count_applicants->execute();
$applicants_result = $count_applicants->get_result();

if ($applicants_result->num_rows > 0) {
    $rowcount = $applicants_result->fetch_assoc();
    $total_applicant = $rowcount['total_applicant'];
} else {
    $total_applicant = 0;
}
//COUNT STAFF ACCOUNT
$count_staffs = $conn->prepare("SELECT COUNT(*) as 'total_staff' FROM staffs");
$count_staffs->execute();
$staffs_result = $count_staffs->get_result();

if ($staffs_result->num_rows > 0) {
    $rowcount = $staffs_result->fetch_assoc();
    $total_staff = $rowcount['total_staff'];
} else {
    $total_staff = 0;
}
//COUNT ADMINS ACCOUNT
$count_admins = $conn->prepare("SELECT COUNT(*) as 'total_admin' FROM admins");
$count_admins->execute();
$admins_result = $count_admins->get_result();

if ($admins_result->num_rows > 0) {
    $rowcount = $admins_result->fetch_assoc();
    $total_admin = $rowcount['total_admin'];
} else {
    $total_admin = 0;
}

//COUNT SYSTEM ACCOUNT
$total_users = $total_applicant + $total_staff + $total_admin;

//COUNT JOB
$count_jobs = $conn->prepare("SELECT COUNT(*) as 'total_job' FROM jobs");
$count_jobs->execute();
$jobs_result = $count_jobs->get_result();

if ($jobs_result->num_rows > 0) {
    $rowcount = $jobs_result->fetch_assoc();
    $total_job = $rowcount['total_job'];
} else {
    $total_job = 0;
}

$count_candidates = $conn->prepare("SELECT COUNT(*) as 'total_candidate' FROM candidates");
$count_candidates->execute();
$candidates_result = $count_candidates->get_result();

if ($candidates_result->num_rows > 0) {
    $rowcount = $candidates_result->fetch_assoc();
    $total_candidate = $rowcount['total_candidate'];
} else {
    $total_candidate = 0;
}

$count_jobs_open = $conn->prepare("SELECT COUNT(*) as 'total_job_open' FROM jobs WHERE status = 'Open'");
$count_jobs_open->execute();
$jobs_open_result = $count_jobs_open->get_result();

if ($jobs_open_result->num_rows > 0) {
    $rowcount = $jobs_open_result->fetch_assoc();
    $total_job_open = $rowcount['total_job_open'];
} else {
    $total_job_open = 0;
}

//COUNT JOBS APPLICANTS
$count_applieds = $conn->prepare("SELECT COUNT(*) as 'total_applied' FROM job_applicants");
$count_applieds->execute();
$applieds_result = $count_applieds->get_result();

if ($applieds_result->num_rows > 0) {
    $rowcount = $applieds_result->fetch_assoc();
    $total_applied = $rowcount['total_applied'];
} else {
    $total_applied = 0;
}

$count_hired_candidates = $conn->prepare("SELECT COUNT(*) as 'total_hired' FROM candidates WHERE candidate_status = 'Approved'");
$count_hired_candidates->execute();
$candidates_result = $count_hired_candidates->get_result();

if ($candidates_result->num_rows > 0) {
    $rowcount = $candidates_result->fetch_assoc();
    $total_hired = $rowcount['total_hired'];
} else {
    $total_hired = 0;
}
