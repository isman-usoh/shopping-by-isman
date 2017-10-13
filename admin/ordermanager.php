<?php
require_once("../include/databaseManager.class.php");
require_once("../include/admin_page.inc.php");
if(!isset($_SESSION['isAdmin'])){
    header("Location: ../login.php?redirect=".$_SERVER['REQUEST_URI']);
}else if($_SESSION['isAdmin'] == false){
    header("Location: ../index.php");
}

$orderStatus = array(0 => "รอการยืนยันคำสั่งซื้อ" ,1 => "ยืนยันคำสั่งซื้อ" ,2 => "รอการชำระเงิน",3 => "ยืนยันการชำระเงิน", 4 =>"กำลังส่งสิ้นค้า" , 99=>"ยกเลิกใบสั่งซื้อ");

drawHeader("Order Manager");
?>
<div id="admin-content">
<input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>
<table width="100%">
    <tr>
        <th>ID</th>
        <th>Customer</th>
        <th>Amount.</th>
        <th>Status.</th>
    </tr>
    <?php
    DatabaseManager::openConnect();
    $queryString = "SELECT Orders.id, CONCAT( Users.firstname,  ' ', Users.lastname ) AS name,
                        (
                        SELECT MAX(Status)
                        From order_status
                        where order_status.orderId = Orders.id
                        ) as status,
                        (
                        SELECT SUM(Total)
                        From order_product
                        where order_product.orderId = Orders.id
                        ) as amount
                    FROM Orders
                    INNER JOIN Users ON Users.Id = Orders.userId";
    $query = mysql_query($queryString);
    while($row = mysql_fetch_assoc($query)){
        echo "<tr>";
        echo "<td><a href='order.php?id=".$row['id']."'>".$row['id']."</a></td>";
        echo "<td>".$row['name']."</td>";
        echo "<td>".$row['amount']."</td>";
        echo "<td>".$orderStatus[$row['status']]."</td>";
        echo "</tr>";
    }
    DatabaseManager::closeConnect();
    ?>
</table>
</div>
<?php
drawFooter();
?>


