<?php
// Start the session
session_start();

// Include your database connection file
require_once 'db.php'; // Adjust this path as needed

// Check if the user is logged in and has admin privileges
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'readWrite') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Retrieve and sanitize input
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING); // Changed from 'name' to 'title'
$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_STRING);
$end = filter_input(INPUT_POST, 'end', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING); // Added handling for 'description'

// Validation (basic example)
if (empty($title) || empty($start) || empty($end)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Insert the event into the database
$query = "INSERT INTO events (title, start, end, description) VALUES (?, ?, ?, ?)"; // Adjusted to include 'description'
$stmt = $conn->prepare($query);

// Check if the query was prepared successfully
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Query preparation failed']);
    exit;
}

$stmt->bind_param("ssss", $title, $start, $end, $description); // Adjusted to bind 'description'

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Event added successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to add event']);
}

$stmt->close();
$conn->close();
?>
