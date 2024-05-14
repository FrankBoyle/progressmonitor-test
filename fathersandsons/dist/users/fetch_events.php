<?php
// Include your database connection script
include 'db.php';

header('Content-Type: application/json');

$stmt = $connection->prepare("SELECT * FROM events");
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
?>
