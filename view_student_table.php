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
    const studentId = currentUrl.searchParams.get('student_id');
    const metadataId = currentUrl.searchParams.get('metadata_id');
    console.log(studentId, metadataId);

    // Construct the API endpoint with the parameters
    const apiUrl = `./users/fetch_data.php`;
    
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
