<?php

// SQL query to get the count of applicants by age
$sql = "SELECT COUNT(*) as count, a.age FROM job_applicants ja JOIN applicants a ON ja.applied_applicant_id = a.applicant_id GROUP BY a.age ORDER BY a.age";
$stmt = $conn->query($sql);
$ages = [];
$counts = [];

while ($row = $stmt->fetch_assoc()) {
    $ages[] = $row['age'];
    $counts[] = (int) $row['count'];
}

// Pass the data to JavaScript (encode as JSON)
$ages_json = json_encode($ages);
$counts_json = json_encode($counts);
