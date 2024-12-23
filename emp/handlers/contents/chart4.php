<?php

// Query to get the number of applicants by gender
$sql = "SELECT COUNT(*) as count, a.gender FROM job_applicants ja JOIN applicants a ON ja.applied_applicant_id = a.applicant_id  GROUP BY gender";
$result = $conn->query($sql);

$applicantCounts = [0, 0];

while ($row = $result->fetch_assoc()) {
    if ($row['gender'] == 'Male') {
        $applicantCounts[0] = $row['count'];  // Male applicants
    } elseif ($row['gender'] == 'Female') {
        $applicantCounts[1] = $row['count'];  // Female applicants
    }
}
