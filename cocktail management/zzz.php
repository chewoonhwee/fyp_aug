<?php
require("config.php");
    //the SQL query to be executed
    $query = "SELECT cocktail_name, quantity FROM report_entries WHERE from_date >= '$startDate' AND to_date <= '$endDate' GROUP BY cocktail_name";
    //storing the result of the executed query
    $result = $conn->query($query);
    //initialize the array to store the processed data
    $jsonArray = array();
    //check if there is any data returned by the SQL Query
    if ($result->num_rows > 0) {
      //Converting the results into an associative array
      while($row = $result->fetch_assoc()) {
        $jsonArrayItem = array();
        $jsonArrayItem['label'] = $row['cocktail_name'];
        $jsonArrayItem['value'] = $row['quantity'];
        //append the above created object into the main array.
        array_push($jsonArray, $jsonArrayItem);
      }
    }
    //Closing the connection to DB
    $conn->close();
    //set the response content type as JSON
    header('Content-type: application/json');
    //output the return value of json encode using the echo function.
    echo json_encode($jsonArray);
    
?>
<script>
    var apiChart = new FusionCharts({
  type: "column2d",
  renderAt: "api-chart-container",
  width: "550",
  height: "350",
  dataFormat: "json",
  dataSource: {
    chart: chartProperties,
    data: chartData
  }
});
$(function() {
  $("#background-btn").click(function() {
    modifyBackground();
  });

  $("#canvas-btn").click(function() {
    modifyCanvas();
  });

  $("#dataplot-btn").click(function() {
    modifyDataplot();
  });

  apiChart.render();
});

function modifyBackground() {
  //to be implemented
}

function modifyCanvas() {
  //to be implemented
}

function modifyDataplot() {
  //to be implemented
}
    </script>
<!DOCTYPE html>
<html>
  <head>
    <title>FusionCharts Column 2D Sample</title>
  </head>
  <body>
    <div id="chart-container">FusionCharts will render here</div>
    <script src="js/jquery-2.1.4.js"></script>
    <script src="js/fusioncharts.js"></script>
    <script src="js/fusioncharts.charts.js"></script>
    <script src="js/themes/fusioncharts.theme.zune.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>

