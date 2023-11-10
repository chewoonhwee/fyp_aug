<?php
require('top+nav.php');
require('config.php');

$startDate = $_GET['start_date'];
$endDate = $_GET['end_date'];

$query = "SELECT cocktail_name, SUM(quantity) as total_quantity FROM report_entries WHERE from_date >= '$startDate' AND to_date <= '$endDate' GROUP BY cocktail_name";
$result = $conn->query($query);

$dataPoints = [];
while ($row = $result->fetch_assoc()) {
    $dataPoints[] = array("label" => $row['cocktail_name'], "y" => $row['total_quantity']);
}

$dataPointsJSON = json_encode($dataPoints, JSON_NUMERIC_CHECK);

$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>  
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
<script>
window.onload = function () {
    var startDate = "<?php echo $startDate; ?>";
    var endDate = "<?php echo $endDate; ?>";

    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light1",
        title: {
            text: "Amount of cocktails sold",
            subtitle: "Selected Date Range: " + startDate + " to " + endDate
        },
        axisY: {
            includeZero: true
        },
        data: [{
            type: "column",
            dataPoints: <?php echo $dataPointsJSON; ?>
        }]
    });
    chart.render();

    // Display the selected date range
    document.getElementById("dateRange").innerText = "Selected Date Range: " + startDate + " - " + endDate;

    // Fetch total revenue data
    fetchTotalRevenue(startDate, endDate);
}

function fetchTotalRevenue(startDate, endDate) {
    // Assuming you have a PHP file that calculates total revenue based on the date range
    var url = "calculate_total_revenue.php?start_date=" + startDate + "&end_date=" + endDate;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            // Display total revenue
            document.getElementById("totalRevenue").innerText = "Total Revenue: $" + data.totalRevenue.toFixed(2);
        })
        .catch(error => console.error('Error:', error));
}
</script>
</head>
<body>
<p id="dateRange"></p>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<p id="totalRevenue"></p>
</body>
</html>
