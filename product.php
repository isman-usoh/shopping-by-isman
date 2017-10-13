<?php
require_once("include/databaseManager.class.php");
require_once("include/user_page.inc.php");

if(!isset($_GET['id'])){
    header("Location:index.php");
}else{
    DatabaseManager::openConnect();
    $queryString = "SELECT *
                         From products
                         where id = '".$_GET['id']."'";
    $query = mysql_query($queryString);
    $product = mysql_fetch_assoc($query);

    $title   = $product['title'];
    $picture   = $product['picture'];
    $amount = $product['amount'];
    $available   = $product['available'];
    $description   = $product['description'];
    DatabaseManager::closeConnect();
    drawHeader($title);
    ?>
    <style type="text/css">
        #product-content{
            padding: 10px;
        }
        #product-image{
            float: left;
            height:auto;
            width: 160px;
        }
        #product-detai{
            float: right;
            width: 600px;
        }
    </style>
    <div id="product-content">
        <div id="product-image">
            <img width="150" height="150" src="<?=$picture?>" alt="<?=$title?>"/>
        </div>
        <div id=product-detai>
            <h3><?=$title?></h3>
            <div>ราคา <?=$amount?> บาท</div>

            <form action="order.php?event=add" style="width: 500px;">
                <input style="display: inline; width: 100px;" type="text" name="count"/>
                <input type="submit" value="Add"/>
            </form>


            <div>รายละเอียด</div>
            <div><?=$description?></div>

            <label for="available12">Available product</label>
            <p id="available12"><?=$available?></p>

        </div>
    </div>
    <?php
    drawFooter();

}