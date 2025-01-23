<?php

require "../../../database/connection.php";

// Get the applied_id from the URL parameter
$applied_id = $_GET['applied_id'];

// Validate the applied_id
if (!is_numeric($applied_id)) {
    echo json_encode(['error' => 'Invalid applied ID']);
    exit;
}

$query = "
    SELECT resume_points, education_points, training_points, experience_points, eligibility_points, competency_points, skill_points 
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
    $education_points,
    $training_points,
    $experience_points,
    $eligibility_points,
    $competency_points,
    $skill_points,
);
$stmt->fetch();

if ($stmt->error) {
    echo json_encode(['error' => $stmt->error]);
    exit;
}



// Return the score details as JSON
echo json_encode([
    'resume_points' => $resume_points,
    'education_points' => $education_points,
    'training_points' => $training_points,
    'experience_points' => $experience_points,
    'eligibility_points' => $eligibility_points,
    'competency_points' => $competency_points,
    'skill_points' => $skill_points,
]);

$stmt->close();
$conn->close();
