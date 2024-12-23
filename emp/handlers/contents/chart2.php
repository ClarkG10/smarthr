<?php
$sql = "SELECT DISTINCT YEAR(applied_date) AS year FROM job_applicants ORDER BY year DESC";
$result = $conn->query($sql);

$years = [];
while ($row = $result->fetch_assoc()) {
    $years[] = $row['year'];
}

if (empty($years)) {
    $years = [date('Y')];
}

$currentYear = date('Y');
$maxYear = $currentYear + 5; 

$applicantCounts = array_fill(0, 12, 0); 

$selectedYear = isset($_GET['year']) ? $_GET['year'] : $currentYear; 

$sql = "SELECT MONTH(applied_date) AS month, COUNT(*) AS total_applicants
        FROM job_applicants
        WHERE YEAR(applied_date) = ?
        GROUP BY MONTH(applied_date)
        ORDER BY MONTH(applied_date)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $selectedYear);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $applicantCounts[$row['month'] - 1] = $row['total_applicants'];
}

