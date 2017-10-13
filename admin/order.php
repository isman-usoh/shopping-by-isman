<?php
require_once("../include/databaseManager.class.php");
require_once("../include/admin_page.inc.php");
if(!isset($_SESSION['isAdmin'])){
    header("Location: ../login.php?redirect=".$_SERVER['REQUEST_URI']);
}else if($_SESSION['isAdmin'] == false){
    header("Location: ../index.php");
}

$orderStatus = array(0 => "รอการยืนยันคำสั่งซื้อ" ,1 => "ยืนยันคำสั่งซื้อ" ,2 => "รอการชำระเงิน",3 => "ยืนยันการชำระเงิน", 4 =>"กำลังส่งสิ้นค้า" , 99=>"ยกเลิกใบสั่งซื้อ");


if(isset($_POST['changeOrderStatus'])){
    DatabaseManager::openConnect();
    $queryString = "INSERT INTO order_status (id,orderId,status,date,note) VALUES (NULL,'".$_GET['id']."','".$_POST['orderStatus']."','".date("Y-m-d H:i:s")."','".$_POST['notes']."')";
    $query = mysql_query($queryString);
    DatabaseManager::closeConnect();
    header("Location: order.php?id=".$_GET['id']);
    exit();
}
if(isset($_POST['cancelOrderStatus'])){
    DatabaseManager::openConnect();
    $queryString = "INSERT INTO order_status (id,orderId,status,date,note) VALUES (NULL,'".$_GET['id']."','99','".date("Y-m-d H:i:s")."','".$_POST['notes']."')";
    $query = mysql_query($queryString);
    echo $queryString;
    DatabaseManager::closeConnect();
    header("Location: order.php?id=".$_GET['id']);
    exit();
}

if(isset($_GET['event'])){
    if($_GET['event'] == "cancel"){
        if(!isset($_GET['id'])){
            header("Location: ordermanager.php");
        }else{
            drawHeader("cancel order");
        ?>
            <div id="admin-content">
        <input type="button" value="Back" onclick=" location = '../admin/ordermanager.php';"/>

        <form action="order.php?event=cancel&cancelsuccess=1&id=<?=$_GET['id']?>" method="post">
            <h3>Cancel order #id <?=$_GET['id']?></h3><br/>

            <label for="notes1">Notes</label>
            <textarea NAME="notes" id="notes1" rows="5" cols="120"></textarea><br/>
            <input type="hidden" name="cancelOrderStatus" value="1"/>
            <input type="submit" value="Yes"/>
            <input type="button" value="No" onclick=" location = 'order.php?id=<?=$_GET['id']?>';"/>
        </form>
            </div>
        <?php
            drawFooter();
        }
    }elseif($_GET['event'] == "changestatus"){
        if(!isset($_GET['id'])){
            header("Location:../admin/ordermanager.php");
        }else{
            drawHeader("Change order status.");
            ?>
        <div id="admin-content">
        <input type="button" value="Back" onclick=" location = '../admin/ordermanager.php';"/>

        <form action="order.php?event=changestatus&chengesuccess=1&id=<?=$_GET['id']?>" method="post">
            <h3>Change order status</h3>
            <select name="orderStatus">
                <?php
                DatabaseManager::openConnect();
                $queryString = "SELECT Max(status) as 'max' From order_status where orderId = '".$_GET['id']."'";
                $query = mysql_query($queryString);
                $status = mysql_fetch_assoc($query);

                for($i=0;$i<count($orderStatus);$i++){
                    if($i==$status['max']+1){
                        echo '<option value="'.$i.'" >'.$orderStatus[$i].'</option>';
                    }else{
                        echo '<option disabled="disabled" value="'.$i.'" >'.$orderStatus[$i].'</option>';
                    }
                }
                DatabaseManager::closeConnect();
                ?>
            </select><br/>
            <label for="notes">Notes</label>
            <textarea NAME="notes" id=notes rows="5" cols="100"></textarea><br/>
            <input type="hidden" name="changeOrderStatus" value="1"/>
            <input type="submit" value="Change Status" <?= $status['max'] == 99 ? 'disabled="disabled"' : ''?>/>
            <input type="button" value="No" onclick=" location = '../admin/ordermanager.php';"/>
        </form>
        </div>
        <?php
            drawFooter();
        }

    }
}
else {
    if(!isset($_GET['id'])){
        header("Location:../admin/ordermanager.php");
    }else{

        DatabaseManager::openConnect();
        $queryString = "SELECT *
                         From Orders
                         where id = '".$_GET['id']."'";
        $query = mysql_query($queryString);
        $order = mysql_fetch_assoc($query);

        $queryString = "SELECT *
                        From Users
                        LEFT JOIN UserDetail on users.id = UserDetail.userId
                        WHERE Users.id = '".$order['userId']."'";
        $query = mysql_query($queryString);
        $user = mysql_fetch_assoc($query);
        drawHeader("View Order");
    ?>
    <div id="admin-content">
    <input type="button" value="Change Order Status" onclick=" location = '../admin/order.php?event=changestatus&id=<?=$_GET['id']?>';"/>
    <input type="button" value="Cancel Order" onclick=" location = '../admin/order.php?event=cancel&id=<?=$_GET['id']?>';"/>

    <p style="color: green;">
        <?php
        if (isset($_GET['cancelsuccess'])) {
            echo "Cancel order successful.";
        }
        if (isset($_GET['chengesuccess'])) {
            echo "Change order status successful.";
        }
        ?>
    </p>

    <h3>Order Detail</h3>
    <div>เลขที : <?=$order['id']?></div>


    <h3>Order Status</h3>
    <table width="100%" >
        <thead>
            <td>Datetime</td>
            <td>Event</td>
            <td>Notes</td>
        </thead>
        <?php
        $queryString = "SELECT * From Order_status where orderId = '".$order['id']."'";
        $query = mysql_query($queryString);
        while($row = mysql_fetch_assoc($query)){
            echo "<tr>";
            echo "<td>".$row['date']."</td>";
            echo "<td>".$orderStatus[$row['status']]."</td>";
            echo "<td>".$row['note']."</td>";
            echo "</tr>";
        }
        ?>
    </table>


    <h3>User.</h3>
    <div >ชิ้อ : <?=$user['firstname']?></div>
    <div >นามสกุล : <?=$user['lastname']?></div>


    <h3>Address.</h3>
    <div>ที่อยู่ : <?=$user['address']?></div>
    <div>จังหวัด : <?=$user['province']?></div>
    <div>รหัสไปราณีย์ : <?=$user['postcode']?></div>

    <h3>Product list</h3>
    <table width="100%">
        <thead>
            <td>Product Name</td>
            <td>Amount</td>
            <td>Count</td>
            <td>Total</td>
        </thead>
        <?php
        $queryString = "SELECT *
                        FROM order_product
                        LEFT JOIN products ON order_product.productId = products.id
                        WHERE order_product.orderId = '".$order['id']."'";
        $query = mysql_query($queryString);
        if(mysql_num_rows($query) == 0){
            echo "<tr><td colspan='4'><p>entry.</p></td></tr>";
        }

        $total = 0;
        while($row = mysql_fetch_assoc($query)){
            echo "<tr>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['amount']."</td>";
            echo "<td>".$row['count']."</td>";
            echo "<td>".$row['total']."</td>";
            echo "</tr>";
            $total += $row['total'];
        }
        echo "<tr>";
        echo "<td colspan='3'>Total</td>";
        echo "<td>".$total."</td>";
        echo "</tr>";
        DatabaseManager::closeConnect();
        ?>
    </table>
    </div>
    <?php

        drawFooter();
    }
}