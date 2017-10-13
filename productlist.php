<?php
require_once("include/databaseManager.class.php");
require_once("include/user_page.inc.php");
drawHeader("Home Page");
?>
<p style="color: green;">
    <?php
    if (isset($_GET['removesuccess'])) {
        echo "Remove product successful.";
    }
    if (isset($_GET['newsuccess'])) {
        echo "Add product successful.";
    }
    if(isset($_GET['modifysuccess'])){
        echo "Update product successful.";
    }
    ?>
</p>
<table width="100%">
    <tr>
        <th>ID</th>
        <th>Picture</th>
        <th>Title</th>
        <th>Amount</th>
        <th>Available</th>
    </tr>
    <?php
    DatabaseManager::openConnect();
    $queryString = "SELECT id, picture, title, amount, available
                        From products";
    $query = mysql_query($queryString);
    while($row = mysql_fetch_assoc($query)){
        echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td><img width='80' height='80' src='".$row['picture']."' alt=''/></td>";
        echo "<td><a href='product.php?id=".$row['id']."'>".$row['title']."</a></td>";
        echo "<td>".$row['amount']."</td>";
        echo "<td>".$row['available']."</td>";
        echo "</tr>";
    }
    DatabaseManager::closeConnect();
    ?>
</table>