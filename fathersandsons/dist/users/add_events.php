<?php
// Start the session
session_start();

// Include your database connection file
require_once 'db.php'; // Adjust this path as needed

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'readWrite') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Retrieve and sanitize input
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

// Validation (basic example)
if (empty($title) || empty($start)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Insert the event into the database
$query = "INSERT INTO events (title, start, description) VALUES (?, ?, ?)";
$stmt = $connection->prepare($query);

// Check if the query was prepared successfully
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("sss", $title, $start, $description);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Event added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add event']);
}

$stmt->close();
$connection->close();
?>
