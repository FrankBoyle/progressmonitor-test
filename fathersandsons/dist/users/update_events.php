<?php
session_start();
require_once 'db.php'; // Adjust this path as needed

// Check user permissions
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'readWrite') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Assuming you're passing an event ID to identify which event to update
$eventId = filter_input(INPUT_POST, 'eventId', FILTER_SANITIZE_NUMBER_INT);
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

if (empty($eventId) || empty($title) || empty($start)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$query = "UPDATE events SET title = :title, start = :start, description = :description WHERE id = :eventId";
$stmt = $connection->prepare($query);

$stmt->bindParam(':title', $title);
$stmt->bindParam(':start', $start);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':eventId', $eventId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Event updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update event']);
}
?>
