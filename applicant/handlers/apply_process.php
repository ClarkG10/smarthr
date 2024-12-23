<?php

require "../../database/connection.php";
require "authenticate.php";
require "user_logged.php";

// dependencies for parsing PDFs and DOCX files
require 'D:/laragon/www/smarthr/vendor/autoload.php'; // e change ni depende saimong path

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
                $text = $pdf->getText(); // kay okay mani 

                // Sanitize and escape the text for use in JavaScript
                $jsonText = json_encode($text);
                echo "<script>console.log('Parsed PDF Text: " . $jsonText . "');</script>"; // Output to browser console e comment lang for debugging rani
            } catch (Exception $e) {
                error_log('Error parsing PDF: ' . $e->getMessage());
            }
        } elseif ($fileExtension == "docx" || $fileExtension == "doc") {
            try {
                $phpWord = IOFactory::load($filePath);
                foreach ($phpWord->getSections() as $section) {
                    foreach ($section->getElements() as $element) {
                        if (method_exists($element, 'getText')) {
                            $text .= $element->getText() . "\n"; //ayaw e mind okay rana
                        } elseif ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
                            foreach ($element->getElements() as $subElement) {
                                if (method_exists($subElement, 'getText')) {
                                    $text .= $subElement->getText(); //ayaw e mind okay rana
                                }
                            }
                        }
                    }
                }

                // Sanitize and escape the text for use in JavaScript
                $jsonText = json_encode($text);
                echo "<script>console.log('Parsed DOCX Text: " . $jsonText . "');</script>"; // Output to browser console e comment lang for debugging rani
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

        $data = [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Analyze the qualifications provided and return a score between 0 and 100 based on relevance to water district job position which is $jobPosition and the minimum qualification to consider are Education: $jobMinimumEducation, Training: $jobMinimumTraining, Experience: $jobMinimumExperience, Eligibility: $jobMinimumEligibility, Competency: $jobMinimumCompetency. Answer with score only. \n\nThese are the qualifications of the applicant: Education: $education \nProgram: $program\nTraining: $training\nExperience: $experience\nEligibility: $eligibility\nCompetency: $competency"
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

        // Execute the cURL request and get the response
        $response = curl_exec($ch);
        if ($response === false) {
            error_log('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return 0;
        }

        curl_close($ch);

        // Parse the Gemini response to get the score
        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $score = trim($decodedResponse['candidates'][0]['content']['parts'][0]['text']);
            return (int)$score;
        }

        return 0;
    }

    // Function to analyze document text using Gemini API
    function analyzeDocumentWithGemini($documentText, $jobPosition, $jobMinimumEducation, $jobMinimumTraining, $jobMinimumExperience, $jobMinimumEligibility, $jobMinimumCompetency)
    {
        $apiKey = 'AIzaSyACF3a4t3vX7gl4x2VjPS1izRDcl09BzYk'; // Replace with your Gemini API key

        $data = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "Analyze the following document text and return a score between 0 and 100 based on relevance to water district job position which is $jobPosition and the minimum qualification to consider are Education: $jobMinimumEducation, Training: $jobMinimumTraining, Experience: $jobMinimumExperience, Eligibility: $jobMinimumEligibility, Competency: $jobMinimumCompetency. Answer with score only. Document Text: $documentText"]
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

        // Execute the cURL request and get the response
        $response = curl_exec($ch);
        if ($response === false) {
            error_log('Curl error: ' . curl_error($ch));
            curl_close($ch);
            return 0;
        }

        curl_close($ch);

        // Parse the Gemini response to get the score
        $decodedResponse = json_decode($response, true);
        if (isset($decodedResponse['candidates'][0]['content']['parts'][0]['text'])) {
            $score = trim($decodedResponse['candidates'][0]['content']['parts'][0]['text']);
            return (int)$score;
        }

        return 0;
    }

    $documentText = '';
    $documentsPoints = 0;
    // overall 100 percent
    $documentWeights = [
        'resume' => 0.45,  // // 40 percent
        'pds' => 0.05, // 5 percent
        'performance_rating' => 0.1, // 10 percent
        'certificate' => 0.15, // 15 percent
        'trainingCertificate' => 0.15, // 15 percent
        'tor' => 0.05, // 5 percent
    ];

    $files = [
        'resume' => $resume_path,
        'pds' => $filepds_path,
        'performance_rating' => $filerating_path,
        'certificate' => $filecertificate_path,
        'trainingCertificate' => $filecertificateTraining_path,
        'tor' => $filetor_path,
    ];

    foreach ($files as $fileType => $file) {
        if (!empty($file)) {
            // Parse PDF, DOC, DOCX to text
            $parsedText = parseFileToText($upload_dir . $file);
            $documentText .= $parsedText . "\n";

            // Analyze document with Gemini API and get points
            $documentScore = analyzeDocumentWithGemini($parsedText, $jobPosition, $jobMinimumEducation, $jobMinimumTraining, $jobMinimumExperience, $jobMinimumEligibility, $jobMinimumCompetency);

            // Apply the weight for the document type
            $documentsPoints += $documentScore * $documentWeights[$fileType];

            // for Log or debug output lang
            error_log("Score for $fileType: $documentScore, Weighted Score: " . ($documentScore * $documentWeights[$fileType]));
        }
    }

    // update program 
    $final_program = $program === `Others` ? $other_program : $program;

    // Get the qualifications score
    $qualificationScore = analyzeQualificationsWithGemini($education, $final_program, $training, $experience, $eligibility, $competency, $jobPosition, $jobMinimumEducation, $jobMinimumTraining, $jobMinimumExperience, $jobMinimumEligibility, $jobMinimumCompetency);

    // Calculate the final rating by averaging the qualifications and documents scores
    $finalRating = ($qualificationScore + $documentsPoints) / 2;

    $insert_sql = $conn->prepare("INSERT INTO `job_applicants`(`applied_applicant_id`, `applied_job_id`, `streets`, `city`, `province`, `postal_code`, `applied_status`, `home_phone`, `facebook_link`, `applied_education`, `applied_program`, `applied_training`, `applied_experience`, `applied_eligibility`, `applied_competency`, `applied_resume`, `applied_file_pds`, `applied_file_rating`, `applied_file_certificate`, `applied_file_training_cert`, `applied_file_tor`, `applied_ratings`, `applied_date`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    // Bind parameters to the SQL query
    $insert_sql->bind_param("iisssssssssssssssssssi", $userId, $applied_job_id, $street, $city, $province, $zip_code, $status, $home_phone, $facebook_profile, $education, $final_program, $training, $experience, $eligibility, $competency, $resume_path, $filepds_path, $filerating_path, $filecertificate_path, $filecertificateTraining_path, $filetor_path, $finalRating);

    if ($insert_sql->execute()) {
        echo '<script>alert("Successfully Submitted."); window.location.href = "http://localhost/smarthr/applicant/applications.php"</script>';
    } else {
        echo 'Error: ' . $insert_sql->error;
    }
}
