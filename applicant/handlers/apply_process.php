<?php

require "../../database/connection.php";
require "authenticate.php";
require "user_logged.php";

// dependencies for parsing PDFs and DOCX files
// require 'D:/laragon/www/smarthr/vendor/autoload.php'; // e change ni depende saimong path 
require '../../vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;

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

    $jobPositionText = json_encode($jobPosition);

    // Log the job position text to the console for debugging purposes
    echo "<script>console.log('Job Position Text: " . $jobPositionText . "');</script>";

    // Qualifications
    $education = $_POST['education'];
    $training = $_POST['training'];
    $experience = $_POST['experience'];
    $eligibility = $_POST['eligibility'];
    $competency = $_POST['competency'];

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
    $filecertificate = $_FILES['file-certificate'];
    $filecertificateTraining = $_FILES['file-certificate-training'];
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

    // upload files
    $resume_path = uploadFile($resume, $upload_dir);
    $filepds_path = uploadFile($filepds, $upload_dir);
    $filerating_path = uploadFile($filerating, $upload_dir);
    $filecertificate_path = uploadFile($filecertificate, $upload_dir);
    $filecertificateTraining_path = uploadFile($filecertificateTraining, $upload_dir);
    $filetor_path = uploadFile($filetor, $upload_dir);

    $resume_path = $resume_path ? $resume_path : '';
    $filepds_path = $filepds_path ? $filepds_path : '';
    $filerating_path = $filerating_path ? $filerating_path : '';
    $filecertificate_path = $filecertificate_path ? $filecertificate_path : '';
    $filecertificateTraining_path = $filecertificateTraining_path ? $filecertificateTraining_path : '';
    $filetor_path = $filetor_path ? $filetor_path : '';

    // Function to parse documents (PDF, DOCX)
    function parseFileToText($filePath)
    {
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $text = '';

        if ($fileExtension == "pdf") {
            $parser = new Parser();
            try {
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();

                // Sanitize and escape the text for use in JavaScript
                $jsonText = json_encode($text);
                echo "<script>console.log('Parsed PDF Text: " . $jsonText . "');</script>";
            } catch (Exception $e) {
                error_log('Error parsing PDF: ' . $e->getMessage());
            }
        } elseif ($fileExtension == "docx" || $fileExtension == "doc") {
            try {
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($filePath);
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        // Check if the element is a Text element
                        if ($element instanceof \PhpOffice\PhpWord\Element\Text) {
                            $text .= $element->getText() . "\n";
                        }
                        // handle multiple text elements. if daghay text element kani ang e run
                        elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            // for each text element sa group of element which also called textRun 
                            // iyang e loop
                            foreach ($element->getElements() as $subElement) {
                                // subElement is mao naning text element and ang e consider nga text element is 
                                // before magka new line 
                                if ($subElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                    // echo "<script>console.log(' subElement Parsed DOCX Text: " . $subElement->getText() . "');</script>";
                                    $text .= $subElement->getText();
                                }
                            }
                            // after each text element mag add tag new line
                            $text .= "\n";
                        }
                    }
                }

                // Sanitize and escape the text for use in JavaScript
                $jsonText = json_encode($text);
                echo "<script>console.log('Parsed DOCX Text: " . $jsonText . "');</script>";
            } catch (Exception $e) {
                error_log('Error parsing DOC/DOCX: ' . $e->getMessage());
            }
        }

        return $text;
    }



    // Function to analyze qualifications using Gemini API
    function analyzeQualificationsWithGemini($education, $program, $training, $experience, $eligibility, $competency, $jobPosition, $jobMinimumEducation, $jobMinimumTraining, $jobMinimumExperience, $jobMinimumEligibility, $jobMinimumCompetency)
    {
        $apiKey = 'AIzaSyACF3a4t3vX7gl4x2VjPS1izRDcl09BzYk';

        $prompt = "
        Analyze the qualifications provided and return a score between 0 and 100 based on relevance to the water district job position: '$jobPosition'.
        
        The minimum requirements for this position are as follows:
        - Education: $jobMinimumEducation
        - Training: $jobMinimumTraining
        - Experience: $jobMinimumExperience
        - Eligibility: $jobMinimumEligibility
        - Competency: $jobMinimumCompetency
        
        The applicant's qualifications are:
        - Education: $education
        - Program: $program
        - Training: $training
        - Experience: $experience
        - Eligibility: $eligibility
        - Competency: $competency
        
        Please assess the applicant's qualifications against these requirements.
        
        Return the score only. If the qualifications do not meet the minimum requirements or are irrelevant, return an precise and appropriate score. No additional text or explanation is required.";

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

        // Initialize cURL for the Gemini API request
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
            return (int)$score;
        }

        return 0;
    }

    // Function to analyze document text using Gemini API
    function analyzeDocumentWithGemini($documentText, $jobPosition, $fileType)
    {
        $apiKey = 'AIzaSyACF3a4t3vX7gl4x2VjPS1izRDcl09BzYk';

        $prompt = "
        Analyze the following document text and return a score between 0 and 100 based on its relevance to the water district job position: '$jobPosition'.
        
        Please pay special attention to the document type: $fileType. It is crucial that you accurately assess the content and format of the document based on the following job requirements and expectations:
        
        1. Resume: Assess the applicant's professional experience, skills, and qualifications.
        2. Personal Data Sheet: Check if all required personal and professional information is provided.
        3. Performance Rating Sheet: Evaluate the applicant's past performance and achievements.
        4. Certificate of Eligibility: Ensure the eligibility criteria are met and appropriately verified.
        5. Training Certificate: Assess whether the training aligns with the job requirements.
        6. Transcript of Records: Evaluate if the educational qualifications meet the standards for the job.

        If the document is invalid, irrelevant, or does not meet the expected content for the specified document type, please return a score of **0**. 
        
        Here is the applicant's document:
    
        $documentText

        Once you have reviewed the document, Please provide the score only for the specified document type. Please pay special attention to the document type: $fileType. If the document is deemed incorrect, please return a score of 0.";

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

        // Initialize cURL for the Gemini API request
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

        // Get the score
        $decodedResponse = json_decode($response, true);

        $json = json_encode($decodedResponse);
        echo "<script>console.log('Gemini Response: " . $json . "');</script>";

        if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $score = trim($decodedResponse['candidates'][0]['content']['parts'][0]['text']);
            return (int)$score;
        }

        return 0;
    }

    $documentText = '';
    $documentsPoints = 0;
    $documentScores = [];
    // overall 100 percent
    $documentWeights = [
        'resume' => 0.65,  // 65 percent 
        'personal_data_sheet' => 0.10, // 10 percent 
        'performance_rating_sheet' => 0.15, // 15 percent 
        'transcript_of_records' => 0.10, // 10 percent 
    ];

    $files = [
        'resume' => $resume_path,
        'personal_data_sheet' => $filepds_path,
        'performance_rating_sheet' => $filerating_path,
        'transcript_of_records' => $filetor_path,
    ];

    foreach ($files as $fileType => $file) {

        $jsonFileType = json_encode($fileType);

        if (!empty($file)) {
            // Parse PDF, DOC, DOCX to text
            $parsedText = parseFileToText($upload_dir . $file);
            $documentText .= $parsedText . "\n";

            $documentScore = analyzeDocumentWithGemini($parsedText, $jobPosition, $jsonFileType);

            // Apply the weight for the document type
            $weightedScore = intval($documentScore) * floatval($documentWeights[$fileType]);
            $documentsPoints += $weightedScore;

            // Store the score for this file type
            $documentScores[$fileType] = $weightedScore;

            // Log document score for debugging purposes
            $jsonScore = json_encode($weightedScore);
            echo "<script>console.log('Document\'s score: " . $jsonScore . "');</script>";  // for debugging
        }
    }


    // update program 
    $final_program = $program === 'Others' ? $other_program : $program;

    // Get the qualifications score
    $qualificationScore = analyzeQualificationsWithGemini($education, $final_program, $training, $experience, $eligibility, $competency, $jobPosition, $jobMinimumEducation, $jobMinimumTraining, $jobMinimumExperience, $jobMinimumEligibility, $jobMinimumCompetency);

    $jsonScore = json_encode($qualificationScore);
    echo "<script>console.log('Qualification score : " . $jsonScore . "');</script>"; // for debugging

    // Calculate the final rating by averaging the qualifications and documents scores
    $finalRating = ($qualificationScore + $documentsPoints) / 2;

    $insert_sql = $conn->prepare("INSERT INTO `job_applicants`(`applied_applicant_id`, `applied_job_id`, `streets`, `city`, `province`, `postal_code`, `applied_status`, `home_phone`, `facebook_link`, `applied_education`, `applied_program`, `applied_training`, `applied_experience`, `applied_eligibility`, `applied_competency`, `applied_resume`, `applied_file_pds`, `applied_file_rating`, `applied_file_certificate`, `applied_file_training_cert`, `applied_file_tor`, `applied_ratings`, `applied_date`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    // Bind parameters to the SQL query
    $insert_sql->bind_param("iisssssssssssssssssssi", $userId, $applied_job_id, $street, $city, $province, $zip_code, $status, $home_phone, $facebook_profile, $education, $final_program, $training, $experience, $eligibility, $competency, $resume_path, $filepds_path, $filerating_path, $filecertificate_path, $filecertificateTraining_path, $filetor_path, $finalRating);

    if ($insert_sql->execute()) {

        $applied_id = $conn->insert_id;
        $resume_score = $documentScores['resume'] ?? 0;
        $pds_score = $documentScores['personal_data_sheet'] ?? 0;
        $performance_rating_score = $documentScores['performance_rating_sheet'] ?? 0;
        $tor_score = $documentScores['transcript_of_records'] ?? 0;

        $insert_scores_sql = $conn->prepare("
        INSERT INTO `applicant_scores` (
            `applicants_id`, 
            `applied_id`, 
            `resume_points`, 
            `personal_data_sheet_points`, 
            `performance_rating_sheet_points`, 
            `transcript_of_records_points`, 
            `qualification_score`
        ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $insert_scores_sql->bind_param(
            "iiiiiii",
            $userId,
            $applied_id,
            $resume_score,
            $pds_score,
            $performance_rating_score,
            $tor_score,
            $qualificationScore
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
