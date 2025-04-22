<?php
header('Content-Type: application/json');

// Database configuration
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'dcfm';
$dbPort = 3307;

// Create connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]));
}

// Get the POST data
$caseData = [
    'case_title' => $_POST['case_title'] ?? '',
    'case_type' => $_POST['case_type'] ?? '',
    'filing_date' => $_POST['filing_date'] ?? '',
    'priority_level' => $_POST['priority_level'] ?? '',
    'plaintiff' => $_POST['plaintiff'] ?? '',
    'defendant' => $_POST['defendant'] ?? '',
    'case_description' => $_POST['case_description'] ?? ''
];

// Validate data
foreach ($caseData as $field => $value) {
    if (empty($value)) {
        echo json_encode([
            'success' => false,
            'message' => "Missing required field: $field"
        ]);
        exit;
    }
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO cases 
    (case_title, case_type, filing_date, priority_level, plaintiff, defendant, case_description, status, created_at) 
    VALUES (?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");

if (!$stmt) {
    echo json_encode([
        'success' => false,
        'message' => 'Prepare failed: ' . $conn->error
    ]);
    exit;
}

// Bind parameters and execute
$stmt->bind_param("sssssss", 
    $caseData['case_title'],
    $caseData['case_type'],
    $caseData['filing_date'],
    $caseData['priority_level'],
    $caseData['plaintiff'],
    $caseData['defendant'],
    $caseData['case_description']
);

if ($stmt->execute()) {
    $caseId = $stmt->insert_id;
    
    // Handle file uploads if any
    $uploadedFiles = [];
    if (!empty($_FILES['documents'])) {
        $uploadDir = 'uploads/cases/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['documents']['name'][$key]);
            $filePath = $uploadDir . uniqid() . '_' . $fileName;
            
            if (move_uploaded_file($tmpName, $filePath)) {
                // Store file info in database
                $fileStmt = $conn->prepare("INSERT INTO case_documents (case_id, file_name, file_path) VALUES (?, ?, ?)");
                $fileStmt->bind_param("iss", $caseId, $fileName, $filePath);
                $fileStmt->execute();
                $fileStmt->close();
                
                $uploadedFiles[] = $fileName;
            }
        }
    }
    
    echo json_encode([
        'success' => true,
        'caseId' => $caseId,
        'message' => 'Case submitted successfully',
        'uploadedFiles' => $uploadedFiles
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error submitting case: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>