<?php

require "../../database/connection.php";
require "authenticate.php";
require "user_logged.php";

// dependencies for parsing PDFs and DOCX files
require '../../vendor/autoload.php'; // Adjust path as needed

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $applied_job_id = $_POST['applied_job_id'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zip_code = $_POST['zip_code'];
    $status = $_POST['status'];
    $home_phone = $_POST['home_number'];
    $facebook_profile = $_POST['facebook_profile'];

    // Retrieve job from POST
    $jobPosition = $_POST['job_position'];
    $jobMinimumEducation = $_POST['job_minimum_education'];
    $jobMinimumTraining = $_POST['job_minimum_training'];
    $jobMinimumExperience = $_POST['job_minimum_experience'];
    $jobMinimumEligibility = $_POST['job_minimum_eligibility'];
    $jobMinimumCompetency = $_POST['job_minimum_competency'];
    $jobDescription = $_POST['job_description'];

    $jobPositionText = json_encode($jobPosition);

    // Log the job position text to the console for debugging purposes
    echo "<script>console.log('Job Position Text: " . $jobPositionText . "');</script>";

    // Qualifications
    $education = $_POST['education'];
    $training = $_POST['training'];
    $experience = $_POST['experience'];
    $eligibility = $_POST['eligibility'];
    $competency = $_POST['competency'];
    $skills = $_POST['skills'];

    $program = isset($_POST['program']) ? $_POST['program'] : 'N/A';
    $other_program = isset($_POST['other-program']) ? $_POST['other-program'] : 'N/A';

    // check if program is already defined and if it exists. e comment ra for debugging lang
    echo "<script>console.log('education Text: " . $education . "');</script>";
    echo "<script>console.log('other program Text: " . $other_program . "');</script>";
    echo "<script>console.log('programText: " . $program . "');</script>";

    // Files for documents
    $resume = $_FILES['resume'];
    $filepds = $_FILES['file-pds'];
    $filerating = $_FILES['file-rating'];
    $filecertificates = $_FILES['file-certificate'];
    $filecertificatesTraining = $_FILES['file-certificate-training'];
    $filetor = $_FILES['file-tor'];

    $upload_dir = "../../uploads/";

    function uploadFile($file, $upload_dir)
    {
        $file_name = basename($file['name']);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Generate a unique file name
        $unique_file_name = uniqid(time() . '_', true) . '.' . $file_type;
        $target_file = $upload_dir . $unique_file_name;

        // Allow only pdf, doc, docx files
        $allowed_types = array("pdf", "doc", "docx");
        if (!in_array($file_type, $allowed_types)) {
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $unique_file_name;
        }

        return false;
    }

    function uploadMultipleFiles($files, $upload_dir)
    {
        $file_paths = [];

        foreach ($files['name'] as $index => $name) {
            $file = [
                'name' => $files['name'][$index],
                'type' => $files['type'][$index],
                'tmp_name' => $files['tmp_name'][$index],
                'error' => $files['error'][$index],
                'size' => $files['size'][$index]
            ];

            $uploaded_path = uploadFile($file, $upload_dir);
            if ($uploaded_path) {
                $file_paths[] = $uploaded_path;
            }
        }
        return $file_paths;
    }

    // Upload single files
    $resume_path = uploadFile($resume, $upload_dir);
    $filepds_path = uploadFile($filepds, $upload_dir);
    $filerating_path = uploadFile($filerating, $upload_dir);
    $filetor_path = uploadFile($filetor, $upload_dir);

    // Upload multiple certificates
    $filecertificate_paths = uploadMultipleFiles($filecertificates, $upload_dir);
    $filecertificateTraining_paths = uploadMultipleFiles($filecertificatesTraining, $upload_dir);

    // Handle paths
    $resume_path = $resume_path ? $resume_path : '';
    $filepds_path = $filepds_path ? $filepds_path : '';
    $filerating_path = $filerating_path ? $filerating_path : '';
    $filetor_path = $filetor_path ? $filetor_path : '';

    $filecertificate_paths = !empty($filecertificate_paths) ? json_encode($filecertificate_paths) : '[]';
    $filecertificateTraining_paths = !empty($filecertificateTraining_paths) ? json_encode($filecertificateTraining_paths) : '[]';

    // Log multiple uploaded paths for debugging
    echo "<script>console.log('File Certificate Paths: " . $filecertificate_paths . "');</script>";
    echo "<script>console.log('File Certificate Training Paths: " . $filecertificateTraining_paths . "');</script>";

    $programs = [
        ['name' => 'Plumbing Technology', 'points' => 20],
        ['name' => 'Electrical Installation and Maintenance', 'points' => 15],
        ['name' => 'Refrigeration and Air-conditioning Servicing', 'points' => 10],
        ['name' => 'Construction Technology', 'points' => 18],
        ['name' => 'Heavy Equipment Operation', 'points' => 12],
        ['name' => 'Industrial Engineering Technology', 'points' => 14],
        ['name' => 'Graphic Design', 'points' => 5],
        ['name' => 'Bachelor of Science in Civil Engineering', 'points' => 20],
        ['name' => 'Bachelor of Science in Environmental Science', 'points' => 18],
        ['name' => 'Bachelor of Science in Public Administration', 'points' => 12],
        ['name' => 'Bachelor of Science in Business Administration', 'points' => 10],
        ['name' => 'Bachelor of Science in Mechanical Engineering', 'points' => 20],
        ['name' => 'Bachelor of Science in Electrical Engineering', 'points' => 15],
        ['name' => 'Bachelor of Science in Geology', 'points' => 14],
        ['name' => 'Bachelor of Science in Statistics', 'points' => 8],
        ['name' => 'Bachelor of Science in Information Technology', 'points' => 5],
        ['name' => 'Bachelor of Science in Accounting', 'points' => 10],
        ['name' => 'Bachelor of Science in Marketing', 'points' => 6],
        ['name' => 'Bachelor of Science in Human Resource Management', 'points' => 6],
        ['name' => 'Bachelor of Science in Chemistry', 'points' => 12],
        ['name' => 'Bachelor of Science in Physics', 'points' => 10],
        ['name' => 'Bachelor of Science in Economics', 'points' => 8],
        ['name' => 'Bachelor of Science in Urban Planning', 'points' => 14],
        ['name' => 'Master of Public Administration', 'points' => 10],
        ['name' => 'Master of Science in Environmental Science', 'points' => 20],
        ['name' => 'Master of Science in Civil Engineering', 'points' => 20],
        ['name' => 'Doctor of Philosophy in Environmental Science', 'points' => 20],
        ['name' => 'Doctor of Philosophy in Civil Engineering', 'points' => 20],
        ['name' => 'Master of Business Administration', 'points' => 10],
        ['name' => 'Master of Science in Urban Planning', 'points' => 18],
        ['name' => 'Master of Science in Project Management', 'points' => 15],
        ['name' => 'Master of Science in Water Resource Management', 'points' => 20],
        ['name' => 'Master of Science in Finance', 'points' => 8]
    ];


    $experience_points = [
        ['experience' => 'Less than 1 year', 'points' => 5],
        ['experience' => '1 year', 'points' => 8],
        ['experience' => '2 years', 'points' => 10],
        ['experience' => '3 years', 'points' => 12],
        ['experience' => '4 years', 'points' => 15],
        ['experience' => '5 years', 'points' => 18],
        ['experience' => '6-9 years', 'points' => 22],
        ['experience' => '10 years', 'points' => 25],
        ['experience' => '15 years', 'points' => 28],
        ['experience' => '20 years', 'points' => 30]
    ];


    $skills = [
        'Water Treatment' => 9,
        'Water Distribution' => 8,
        'Customer Service' => 7,
        'Maintenance and Repair' => 6,
        'Water Conservation' => 8,
        'Quality Control' => 10,
        'System Design and Engineering' => 9,
        'Regulatory Compliance' => 7,
        'Project Management' => 8,
        'Financial Management' => 6,
        'Supply Chain Management' => 7,
        'Risk Management' => 6,
        'Infrastructure Development' => 8,
        'Staff Training and Development' => 7,
        'Emergency Response' => 9,
        'Technology Implementation' => 8,
        'Data Analysis and Reporting' => 7,
        'Community Engagement' => 6,
        'Environmental Impact Assessment' => 7,
        'Water Resource Management' => 9,
        'Legal and Policy Knowledge' => 6,
        'Supply and Demand Forecasting' => 6,
        'Infrastructure Asset Management' => 8,
        'Water Sampling and Testing' => 7,
        'Customer Billing and Account Management' => 6,
        'Operational Efficiency' => 8,
        'Health and Safety Standards' => 7,
        'Water Loss Control' => 8,
    ];

    $education_points = [
        ['level' => 'Elementary', 'points' => 5],
        ['level' => 'High School', 'points' => 10],
        ['level' => 'Vocational', 'points' => 15],
        ['level' => 'College', 'points' => 18],
        ['level' => 'Postgraduate', 'points' => 20]
    ];


    $minimumQualification = [
        "education" => $_POST['job_minimum_education'] ?? '',
        "training certificates" => $_POST['job_minimum_training'] ?? '',
        "experience" => $_POST['job_minimum_experience'] ?? '',
        "eligibility" => $_POST['job_minimum_eligibility'] ?? '',
        "competency" => $_POST['job_minimum_competency'] ?? '',
        "skills" => $_POST['job_minimum_skills'] ?? '',
    ];

    echo "<script>console.log('Job Qualification: " . json_encode($minimumQualification) . "');</script>";

    $applicantQualification = [
        "education" => $_POST['education'] ?? '',
        "training certificates" => $_POST['training'] ?? 'None',
        "experience" => $_POST['experience'] ?? '',
        "eligibility" => $_POST['eligibility'] ?? 'None',
        "competency" => $_POST['competency'] ?? 'None',
        "skills" => $_POST['skills'] ?? 'None',
    ];

    echo "<script>console.log('Applicant Qualification: " . json_encode($applicantQualification) . "');</script>";

    $qualificationPoints = [
        "education" => 20,
        "training certificates" => 10,
        "experience" => 30,
        "eligibility" => 15,
        "competency" => 15,
        "skills" => 10,
    ];


    function analyzeQualificationsWithGemini($applicantQualification, $minimumQualification, $maxQualificationPoints, $jobPosition, $jobDescription, $category)
    {

        $apiKey = 'AIzaSyACF3a4t3vX7gl4x2VjPS1izRDcl09BzYk';

        $prompt = "
        Assess the applicant's qualifications relative to the job position: '$jobPosition' - $jobDescription.
        
        Scoring Guidelines:
        - If the applicant's qualifications exceed the minimum requirements, allocate additional points based on the degree of relevance, up to the maximum allowed points.
        - Qualifications that are not part of the minimum requirements but demonstrate value to the position should still receive partial points.
        - If a qualification is irrelevant or does not meet the minimum requirements, deduct points proportionally to the gap, ensuring fairness.
        
        The minimum qualifications for this position are as follows:
        $category - $minimumQualification
        
        The applicant's qualifications are:
        $category - $applicantQualification
    
        The maximum points available for $category in qualification criterion is: $maxQualificationPoints
    
        Calculate a precise score based on these instructions. 
        - Stronger alignment with required qualifications should yield higher scores.
        - Qualifications exceeding the minimum should add value and should be rewarded.
        - If qualifications fall short of the minimum, reduce the score proportionally.
        
        Return only the final score as a number with decimals. Do not include any additional text or explanation.";

        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => $prompt
                        ]
                    ]
                ]
            ]
        ];

        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . $apiKey);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if ($response === false) {
            error_log('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return 0;
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $score = trim($decodedResponse['candidates'][0]['content']['parts'][0]['text']);
            return is_numeric($score) ? (float)$score : 0;
        }

        return 0;
    }

    $finalQualificationScore = 0;
    $scores = []; // Initialize the scores array

    foreach ($minimumQualification as $index => $minRequirement) {
        $applicantData = $applicantQualification[$index] ?? '';
        $maxQualificationPoints = $qualificationPoints[$index] ?? '';

        $score = analyzeQualificationsWithGemini(
            $applicantData,
            $minRequirement,
            $maxQualificationPoints,
            $jobPosition,
            $jobDescription,
            $index
        );

        // Log the score for debugging
        echo "<script>console.log('Score for $index: " . $score . "');</script>";

        $finalQualificationScore += $score;

        $scores[$index] = $score;
    }

    $partialRating = ($finalQualificationScore / 100) * 50;
    $finalRating = $partialRating;

    // Log the final qualification score
    echo "<script>console.log('Final Qualification Score: " . $finalQualificationScore . "');</script>";

    // update program 
    $final_program = $program === 'Others' ? $other_program : $program;

    $insert_sql = $conn->prepare("INSERT INTO `job_applicants`(`applied_applicant_id`, `applied_job_id`, `streets`, `city`, `province`, `postal_code`, `applied_status`, `home_phone`, `facebook_link`, `applied_education`, `applied_program`, `applied_training`, `applied_experience`, `applied_eligibility`, `applied_competency`, `applied_resume`, `applied_file_pds`, `applied_file_rating`, `applied_file_certificate`, `applied_file_training_cert`, `applied_file_tor`, `partial_rating`,`applied_ratings`, `applied_date`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    // Bind parameters to the SQL query
    $insert_sql->bind_param("iisssssssssssssssssssdd", $userId, $applied_job_id, $street, $city, $province, $zip_code, $status, $home_phone, $facebook_profile, $education, $final_program, $training, $experience, $eligibility, $competency, $resume_path, $filepds_path, $filerating_path, $filecertificate_paths, $filecertificateTraining_paths, $filetor_path, $partialRating, $finalRating);

    if ($insert_sql->execute()) {

        $applied_id = $conn->insert_id;
        $resume_score = 0.00;
        $education_score = $scores['education'] ?? 0.00;
        $training_certifates_score = $scores['training certificates'] ?? 0.00;
        $experience_score = $scores['experience'] ?? 0.00;
        $eligibility_score = $scores['eligibility'] ?? 0.00;
        $competency_score = $scores['competency'] ?? 0.00;
        $skills_score = $scores['skills'] ?? 0.00;

        // console log scores
        echo "<script>console.log('Resume Score: " . $competency_score . "');</script>";

        $insert_scores_sql = $conn->prepare("
        INSERT INTO `applicant_scores` (
            `applicants_id`, 
            `applied_id`, 
            `resume_points`, 
            `education_points`, 
            `training_points`, 
            `experience_points`, 
            `eligibility_points`, 
            `competency_points`,
            `skill_points`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");


        $insert_scores_sql->bind_param(
            "iiddddddd",
            $userId,
            $applied_id,
            $resume_score,
            $education_score,
            $training_certifates_score,
            $experience_score,
            $eligibility_score,
            $competency_score,
            $skills_score
        );

        if ($insert_scores_sql->execute()) {
            echo '<script>alert("Successfully Submitted."); window.location.href = "http://localhost/smarthr/applicant/applications.php"</script>';
        } else {
            error_log('Error inserting scores: ' . $insert_scores_sql->error);
            echo 'Error inserting scores: ' . $insert_scores_sql->error;
        }
    } else {
        error_log('Error inserting applicant data: ' . $insert_sql->error);
        echo 'Error inserting applicant data: ' . $insert_sql->error;
    }
}
