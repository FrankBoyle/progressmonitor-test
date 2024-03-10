<?php
include('auth_session.php');
// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');

$sql = "SELECT * FROM Scores";
$result = $conn->query($sql);

$scores = array();

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $scores[] = $row;
    }
    echo json_encode($scores);
} else {
    echo "0 results";
}

$conn->close();
?>
