<?php
// Include the database connection
require('config.php');
require('top+nav.php');
?>
<body>
<title>Report Graph</title>
    <form action="generate_graph.php" method="get">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>

        <button type="submit">Generate Graph</button>
    </form>
</body>
</html>