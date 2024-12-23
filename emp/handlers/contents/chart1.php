<?php
$sql = "SELECT * FROM jobs";
$result = $conn->query($sql);

$jobPositions = [];
$jobIds = []; // Store job IDs
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jobPositions[] = $row['job_position'];  // Store job positions
        $jobIds[] = $row['job_id'];  // Store job IDs
    }
}

$numApplicantsArray = []; // Initialize the array

foreach ($jobIds as $jobId) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS num_applicants FROM job_applicants WHERE applied_job_id = ?");
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $stmtRes = $stmt->get_result();

    while ($row = $stmtRes->fetch_assoc()) {
        $numApplicantsArray[] = (int)$row['num_applicants'];
    }
}
