<?php
// Include the necessary files (config.php and top+nav.php)
require('config.php');
require('top+nav.php');

// Fetch data from the alcohol_inventory table
$query = "SELECT alcohol_name, quantity FROM alcohol_inventory";
$result = $conn->query($query);

// Fetch the data into an array
$dataPoints = [];
while ($row = $result->fetch_assoc()) {
    $label = $row['alcohol_name'];
    $y = $row['quantity'];

    // Set the color to red for quantity 1 or 0
    $color = ($y <= 1) ? "red" : "#90EE90";

    $dataPoints[] = array("label" => $label, "y" => $y, "color" => $color);
}

// Encode the data into JSON
$dataPointsJSON = json_encode($dataPoints, JSON_NUMERIC_CHECK);

// Close the database connection
$conn->close();
?>

<!DOCTYPE HTML>
<html>
<head>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script>
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                theme: "light1",
                title: {
                    text: "Alcohol Inventory Pie Chart"
                },
                data: [{
                    type: "pie",
                    startAngle: 240,
                    indexLabel: "{label} {y}",
                    indexLabelFontColor: "{color}",
                    dataPoints: <?php echo $dataPointsJSON; ?>
                }]
            });
            chart.render();
        }
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div id="chartContainer" style="height: 370px; width: 100%; margin-top: 20px;"></div>
</body>
</html>
