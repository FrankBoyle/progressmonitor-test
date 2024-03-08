<?php
include ('./users/fetch_data.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

foreach ($students as $student) {
    if ($student['student_id'] == $studentId) { // If the IDs match
        $studentName = $student['name']; // Get the student name
        break;
    }
}

if (isset($_GET['metadata_id'])) {
    $selectedMetadataId = $_GET['metadata_id'];

    // Now fetch the corresponding category name based on this metadata_id
    foreach ($metadataEntries as $metadataEntry) {
        if ($metadataEntry['metadata_id'] == $selectedMetadataId) {
            $selectedCategoryName = $metadataEntry['category_name'];
            break; // We found our category, no need to continue the loop
        }
    }
} else {
    // Optional: Handle cases where no metadata_id is specified, if needed
    // $selectedCategoryName = "Default Category or message"; // for example
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>W2UI Table Example</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/w2ui@1.5/dist/w2ui.min.css" />
</head>
<body>

<div id="grid" style="width: 100%; height: 400px;"></div>

<script src="https://cdn.jsdelivr.net/npm/w2ui@1.5/dist/w2ui.min.js"></script>
<script>
// Function to fetch data based on URL parameters
function fetchData() {
    // Get the current URL
    const currentUrl = new URL(window.location.href);
    
    // Extract parameters from URL
    const student_id = currentUrl.searchParams.get('student_id');
    const metadata_id = currentUrl.searchParams.get('metadata_id');
    console.log(student_id, metadata_id);

    // Construct the API endpoint with the parameters
    const apiUrl = `./users/fetch_data.php?student_id=${student_id}&metadata_id=${metadata_id}`;
    
    // Fetch data from your PHP backend using the constructed URL
    fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
            // Assuming your data is an array of objects
            initGrid(data);
        })
        .catch(error => console.error('Error fetching data:', error));
}

// Initialize W2UI Grid with fetched data (function remains the same)
function initGrid(data) {
    // Code for initializing the grid goes here
}

// Call fetchData on page load to populate the grid
document.addEventListener('DOMContentLoaded', fetchData);
</script>

</body>
</html>
