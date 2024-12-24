<?php

require "../../../database/connection.php";

// Get the applied_id from the URL parameter
$applied_id = $_GET['applied_id'];

// Validate the applied_id
if (!is_numeric($applied_id)) {
    echo json_encode(['error' => 'Invalid applied ID']);
    exit;
}

// Query to fetch the score details from applicant_scores table
$query = "
    SELECT resume_points, personal_data_sheet_points, performance_rating_sheet_points, 
           certificate_of_eligibility_points, training_certificate_points, transcript_of_records_points, 
           qualification_score 
    FROM applicant_scores 
    WHERE applied_id = ?
";

$stmt = $conn->prepare($query);

if ($stmt === false) {
    echo json_encode(['error' => 'Failed to prepare query']);
    exit;
}

$stmt->bind_param("i", $applied_id); // Bind the parameter
$stmt->execute();
$stmt->bind_result(
    $resume_points,
    $personal_data_sheet_points,
    $performance_rating_sheet_points,
    $certificate_of_eligibility_points,
    $training_certificate_points,
    $transcript_of_records_points,
    $qualification_score
);
$stmt->fetch();

if ($stmt->error) {
    echo json_encode(['error' => $stmt->error]);
    exit;
}

// Return the score details as JSON
echo json_encode([
    'resume_points' => $resume_points,
    'personal_data_sheet_points' => $personal_data_sheet_points,
    'performance_rating_sheet_points' => $performance_rating_sheet_points,
    'certificate_of_eligibility_points' => $certificate_of_eligibility_points,
    'training_certificate_points' => $training_certificate_points,
    'transcript_of_records_points' => $transcript_of_records_points,
    'qualification_score' => $qualification_score
]);

$stmt->close();
$conn->close();
