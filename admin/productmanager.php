<?php
require_once("../include/databaseManager.class.php");
require_once("../include/admin_page.inc.php");
if(!isset($_SESSION['isAdmin'])){
    header("Location: ../login.php?redirect=".$_SERVER['REQUEST_URI']);
}else if($_SESSION['isAdmin'] == false){
    header("Location: ../index.php");
}
drawHeader("Product Manager");
?>
<div id="admin-content">
<input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>
<input type="button" value="New Product" onclick=" location = '../admin/product.php?event=new';"/>
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
        echo "<td><img width='80' height='80' src='../".$row['picture']."' alt=''/></td>";
        echo "<td><a href='product.php?id=".$row['id']."'>".$row['title']."</a></td>";
        echo "<td>".$row['amount']."</td>";
        echo "<td>".$row['available']."</td>";
//        echo "<td>".($row['usertype'] == 0 ? "User" : "Admin" )."</td>";
//        echo "<td><a href='user.php?event=modify&id=".$row['id']."'>Modify</a></td>";
//        echo "<td><a href='user.php?event=remove&id=".$row['id']."'>Remove</a></td>";
        echo "</tr>";
    }
    DatabaseManager::closeConnect();
    ?>
</table>
</div>
<?php
drawFooter();
?>


