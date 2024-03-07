<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Performance Data Display</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 1000px;
            margin: auto;
        }
        #dataTable {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Performance Data</h2>
        <div id="dataTable"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            fetchPerformanceData();
        });

        function fetchPerformanceData() {
            $.ajax({
                url: './users/fetch_data.php', // Your PHP endpoint
                type: 'GET', // or 'POST', as required by your PHP endpoint
                dataType: 'json',
                success: function(response) {
                    // Assuming response contains performanceData and scoreNames
                    initializeHandsontable(response.performanceData, response.scoreNames);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data:", error);
                }
            });
        }

        function initializeHandsontable(performanceData, scoreNames) {
            const dataForHandsonTable = performanceData.map(item => {
                const rowData = {};
                rowData['Date'] = item.score_date;
                scoreNames.forEach((name, index) => {
                    rowData[name] = item['score' + (index + 1)];
                });
                return rowData;
            });

            const columns = [{data: 'Date', type: 'date', dateFormat: 'MM/DD/YYYY'}];
            scoreNames.forEach(name => {
                columns.push({data: name, type: 'text'});
            });

            const container = document.getElementById('dataTable');
            const hot = new Handsontable(container, {
                data: dataForHandsonTable,
                columns: columns,
                colHeaders: ['Date', ...scoreNames],
                rowHeaders: true,
                stretchH: 'all',
                width: '100%',
                height: 'auto',
                columnSorting: true,
                contextMenu: true,
                manualRowMove: true,
                manualColumnMove: true,
                filters: true,
                dropdownMenu: true,
            });
        }
    </script>
</body>
</html>
