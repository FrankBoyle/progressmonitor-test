<?php
// Start the session
session_start();

// Include your database connection file
require_once 'db.php'; // Adjust this path as needed

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'readWrite') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Retrieve and sanitize input
$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
$start = filter_input(INPUT_POST, 'start', FILTER_SANITIZE_STRING);
$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

// Validation
if (empty($title) || empty($start)) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

// Prepare the SQL query
$query = "INSERT INTO events (title, start, description) VALUES (:title, :start, :description)";

try {
    // Prepare the statement
    $stmt = $connection->prepare($query);

    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':start', $start);
    $stmt->bindParam(':description', $description);

    // Execute the statement and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event added successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add event']);
    }
} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

// No need to explicitly close the statement or connection
// PDO does this automatically when the object is destroyed
?>
