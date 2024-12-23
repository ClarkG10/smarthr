<?php

require "../../../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $jobPosition = $_POST['jobPosition'];
    $plantillaItem = $_POST['plantillaItem'];
    $payGrade = $_POST['payGrade'];
    $monthlySalary = $_POST['monthlySalary'];
    $education = $_POST['education'];
    $training = $_POST['training'];
    $experience = $_POST['experience'];
    $eligibility = $_POST['eligibility'];
    $competency = $_POST['competency'];
    $place = $_POST['place'];
    $openPosition = $_POST['openPosition'];
    $status = "Open";

    $check_position = $conn->prepare("SELECT * FROM JOBS WHERE job_position = ?");
    $check_position->bind_param("s", $jobPosition);
    $check_position->execute();
    $check_result = $check_position->get_result();

    if ($check_result->num_rows === 0) {
        $insert_sql = $conn->prepare("INSERT INTO `jobs`(`job_position`, `plantilla_item`, `pay_grade`, `monthly_salary`, `education`, `training`, `experience`, `eligibility`, `competency`, `open_position`, `place`, `status`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $insert_sql->bind_param("siissssssiss", $jobPosition, $plantillaItem, $payGrade, $monthlySalary, $education, $training, $experience, $eligibility, $competency, $openPosition, $place, $status);
        if ($insert_sql->execute()) {
            echo '<script> alert("Successfully Added Job."); location.href = "../../manage_jobs.php"; </script>';
            exit();
        }
    } else {
        echo '<script> alert("Job Position Title already exist."); location.href = "../../manage_jobs.php" </script>';
        exit();
    }
}
