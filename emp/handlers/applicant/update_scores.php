<?php
require "../../../database/connection.php";

// Define the maximum points for each criterion
$qualificationPoints = [
    "education" => 20,
    "training" => 10,
    "experience" => 30,
    "eligibility" => 15,
    "competency" => 15,
    "skills" => 10,
    "resume" => 100,
];

// Get the applied ID and form data
$appliedId = $_POST['applied_id'];
$resume_points = $_POST['resume_points'];
$education_points = $_POST['education_points'];
$training_points = $_POST['training_points'];
$experience_points = $_POST['experience_points'];
$eligibility_points = $_POST['eligibility_points'];
$competency_points = $_POST['competency_points'];
$skill_points = $_POST['skill_points'];

// Validate the input scores
if (
    $resume_points > $qualificationPoints['resume'] ||
    $education_points > $qualificationPoints['education'] ||
    $training_points > $qualificationPoints['training'] ||
    $experience_points > $qualificationPoints['experience'] ||
    $eligibility_points > $qualificationPoints['eligibility'] ||
    $competency_points > $qualificationPoints['competency'] ||
    $skill_points > $qualificationPoints['skills']
) {
    echo "<script>
        alert('Input scores exceed maximum allowed points.');
        window.location.href = 'http://localhost/smarthr/emp/view_details.php?application=" . $appliedId . "';
    </script>";
    exit;
}

// Check if the applied_id exists in the applicant_scores table
$query = "SELECT * FROM applicant_scores WHERE applied_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $appliedId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Calculate the total score
    $total_score = (($resume_points + $education_points + $training_points + $experience_points + $eligibility_points + $competency_points + $skill_points) / 200) * 100;

    // Update the applicant's scores in applicant_scores
    $updateQuery = "UPDATE applicant_scores SET 
                    resume_points = ?, 
                    education_points = ?, 
                    training_points = ?, 
                    experience_points = ?, 
                    eligibility_points = ?, 
                    competency_points = ?, 
                    skill_points = ? 
                    WHERE applied_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param(
        "dddddddd",
        $resume_points,
        $education_points,
        $training_points,
        $experience_points,
        $eligibility_points,
        $competency_points,
        $skill_points,
        $appliedId
    );
    $stmt->execute();

    // Update the applied_ratings in job_applicants
    $ratingUpdateQuery = "UPDATE job_applicants SET applied_ratings = ? WHERE applied_id = ?";
    $ratingStmt = $conn->prepare($ratingUpdateQuery);
    $ratingStmt->bind_param("di", $total_score, $appliedId);
    $ratingStmt->execute();

    // Redirect with a success message
    echo "<script>
        alert('Scores updated successfully!');
        window.location.href = 'http://localhost/smarthr/emp/view_details.php?application=" . $appliedId . "';
    </script>";
} else {
    echo "<script>
        alert('The applied ID does not exist in the applicant scores table.');
        window.location.href = 'http://localhost/smarthr/emp/view_details.php';
    </script>";
}
