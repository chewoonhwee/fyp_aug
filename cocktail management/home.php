<?php
require('top+nav.php');
// Include the database connection
require('config.php');
?>
    <main>
        <div class="content">
            <?php

                require('config.php');
                $query = "SELECT * FROM alcohol_inventory";
                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0) {
                    echo "<table><tr><th>Alcohol Category</th><th>Alcohol Name</th><th>Price</th><th>Quantity</th></tr>";

                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>".$row['alcohol_category']."</td><td>".$row['alcohol_name']."</td><td>RM ".$row['price']."</td><td>".$row['quantity']."</td></tr>";
                    }

                    echo "</table>";
                } else {
                    echo "No cocktails found in the database.";
                }
            ?>
        </div>
    </main>
</body>
</html>