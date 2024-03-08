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
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.css">
</head>
<body>

<div id="grid" style="width: 100%; height: 400px;"></div>

<script src="https://rawgit.com/vitmalina/w2ui/master/dist/w2ui.min.js"></script>
<script>
    // Get the metadata_id from the URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const metadata_id = urlParams.get('metadata_id');
    var scoreNamesFromPHP = <?php echo json_encode($scoreNames); ?>;
    </script>
<script>
function initGrid(data) {
    // Convert your PHP data to a format suitable for W2UI grid
    const records = data.Behavior.map((name, index) => ({
        recid: index + 1, // W2UI requires a unique 'recid' for each row
        scoreName: name
    }));

    $('#grid').w2grid({
        name: 'scoreGrid',
        show: {
            toolbar: true,
            footer: true,
            header: 'Behavior Scores'
        },
        columns: [
            { field: 'recid', caption: 'ID', size: '50px', sortable: true, attr: 'align=center' },
            { field: 'scoreName', caption: 'Score Name', size: '100%', sortable: true }
        ],
        records: records
    });
}

// Call initGrid on page load to populate the grid
document.addEventListener('DOMContentLoaded', () => initGrid(scoreNamesFromPHP));

</script>

</body>
</html>
