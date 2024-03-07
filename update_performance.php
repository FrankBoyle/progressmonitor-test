<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('./users/db.php');  
header('Content-Type: application/json');

// Validate and sanitize input before using
$performanceId = filter_input(INPUT_POST, 'performance_id', FILTER_SANITIZE_NUMBER_INT);
$fieldName = filter_input(INPUT_POST, 'field_name', FILTER_SANITIZE_STRING);
$newValue = filter_input(INPUT_POST, 'new_value', FILTER_DEFAULT); // Use specific filter as needed
$studentId = filter_input(INPUT_POST, 'student_id', FILTER_SANITIZE_NUMBER_INT);
$metadata_id = filter_input(INPUT_POST, 'metadata_id', FILTER_SANITIZE_NUMBER_INT);

if (!$performanceId || !$fieldName) {
    handleError("Invalid or missing data provided.");
    exit;
}

// Ensure the field is allowed to be updated
$allowedFields = ['score_date', 'score1', 'score2', 'score3', 'score4', 'score5', 'score6', 'score7', 'score8', 'score9', 'score10'];
if (!in_array($fieldName, $allowedFields)) {
    handleError("Invalid field specified.");
    exit;
}

if ($fieldName === 'score_date') {
    // Validate the date format before proceeding
    $d = DateTime::createFromFormat('Y-m-d', $newValue);
    if (!$d || $d->format('Y-m-d') !== $newValue) {
        handleError("Invalid date format. Expected 'Y-m-d'.");
        exit;
    }
    
    // Check for duplicate dates
    $stmt = $connection->prepare("
        SELECT COUNT(*) 
        FROM Performance 
        WHERE student_id = ? AND score_date = ? AND metadata_id = ? AND performance_id != ?
    ");
    $stmt->execute([$studentId, $newValue, $metadata_id, $performanceId]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        handleError("Duplicate date not allowed.");
        exit;
    }
}

// Proceed with the update if no duplicates and data is valid
updatePerformance($connection, $performanceId, $fieldName, $newValue);

function updatePerformance($connection, $performanceId, $fieldName, $newValue) {
    $sql = "UPDATE `Performance` SET `$fieldName` = ? WHERE `performance_id` = ?";
    $stmt = $connection->prepare($sql);

    if ($stmt->execute([$newValue, $performanceId])) {
        sendResponse(["success" => true]);
    } else {
        handleError("Database error: " . implode(", ", $stmt->errorInfo()));
    }
}

function handleError($errorMessage) {
    sendResponse(["success" => false, "error" => $errorMessage]);
}

function sendResponse($response) {
    echo json_encode($response);
    exit;
}
?>






