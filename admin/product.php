<?php
require_once("../include/databaseManager.class.php");
require_once("../include/admin_page.inc.php");
if(!isset($_SESSION['isAdmin'])){
    header("Location: ../login.php?redirect=".$_SERVER['REQUEST_URI']);
}else if($_SESSION['isAdmin'] == false){
    header("Location: ../index.php");
}
$error_message = "";
$date_valid = true;

if (isset($_POST['modify']) || isset($_POST['new'])) {
    if (strlen($_POST['title']) <= 4) {
        $error_message .= "  ชื่อสินค้าส้นเกินไป";
        $date_valid = false;
    }

    if (strlen($_POST['description']) <= 10) {
        $error_message .= "  ยังไม่ระบุรายละเอียดสิ้นค้า";
        $date_valid = false;
    }

    if (is_double($_POST['price'])) {
        $error_message .= "  ระบุราคาสิ้นค้าไม่ถูกต้อง";
        $date_valid = false;
    }

    if (is_double($_POST['available'])) {
        $error_message .= "  ระบุจำนวนสิ้นค้าไม่ถูกต้อง";
        $date_valid = false;
    }

    if ($date_valid) {
        if($_GET['event'] == "modify"){
            DatabaseManager::openConnect();
            $queryString = "UPDATE  Products
                            SET  title = '".$_POST['title']."' ,
                            amount = '".$_POST['amount']."',
                            available = '".$_POST['available']."',
                            description = '".$_POST['description']."'
                            WHERE id = '".$_GET['id']."'";
            @mysql_query($queryString);
            DatabaseManager::closeConnect();
            header("Location: ../admin/productmanager.php?modifysuccess=".$_GET['id']);
        }else if($_GET['event'] == "new"){
            DatabaseManager::openConnect();
            $queryString = "INSERT INTO Products
                        (id , title , description , picture , amount , available)VALUES
                        (NULL ,  '" . $_POST['title'] . "',  '" . $_POST['description'] . "',  'image/product/nophotoicon.jpg',  '" . $_POST['price'] . "',  '".$_POST['available']."');";
            $query = mysql_query($queryString);
            $id = mysql_insert_id();
            DatabaseManager::closeConnect();
            header("Location: ../admin/productmanager.php?newsuccess=".$id);
        }
    }
}
if (isset($_POST['remove'])){
    if($_GET['event'] == "remove"){
        DatabaseManager::openConnect();

        $queryString = "DELETE From Products where id = '".$_GET['id']."'";
        $query = mysql_query($queryString);
        DatabaseManager::closeConnect();
        header("Location: ../admin/productmanager.php?removesuccess=".$_GET['id']);
    }
}
if(isset($_GET['event'])){
    if($_GET['event'] == "modify"){
    if(!isset($_GET['id'])){
        header("Location: ../admin/productmanager.php");
    }else{
        $title   = "";
        $picture   = "";
        $amount  = 0;
        $available   = 0;
        $description   = "";

        if(isset($_POST['title']) && isset($_POST['amount']) && isset($_POST['available']) && isset($_POST['description'])){
            $title   = $_POST['title'];
            $picture   = $_POST['picture'];
            $amount  = $_POST['amount'];
            $available   = $_POST['available'];
            $description   = $_POST['description'];
        }else{
            DatabaseManager::openConnect();
            $queryString = "SELECT *
                         From products
                         where id = '".$_GET['id']."'";
            $query = mysql_query($queryString);
            $product = mysql_fetch_assoc($query);

            $title   = $product['title'];
            $picture   = $product['picture'];
            $amount  = $product['amount'];
            $available   = $product['available'];
            $description   = $product['description'];
            DatabaseManager::closeConnect();
        }
    drawHeader("Edit");
        ?>
    <div id="admin-content">
    <input type="button" value="Back" onclick=" location = '../admin/productmanager.php';"/>
    <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>

    <form action="product.php?event=modify&id=<?=$_GET['id']?>" method="post">

        <label for="title1">Name</label>
        <input type="text" name="title" id="title1" value="<?=$title?>"><br/>

        <label for="description1">Description</label>
        <textarea name="description" id="description1"><?=$description?></textarea><br/>

        <label for="amount1">amount</label>
        <input type="text" name="amount" id="amount1" value="<?=$amount?>"><br/>

        <label for="available1">Available</label>
        <input type="text" name="available" id="available1" value="<?=$available?>"><br/>

        <label for="image1">Image</label>
        <input type="file" name="image" id="image1"/><br/>

        <input type="hidden" name="picture" value="<?=$picture?>">
        <input type="hidden" name="new" value="1"/>
        <label style="color: red;"><?=$error_message?></label><br/>
        <input type="submit" value="Save"/>
    </form>
    </div>
    <?php
    drawFooter();
    }
}
    else if($_GET['event'] == "new"){
    drawHeader("New Product");
    ?>
    <div id="admin-content">
    <input type="button" value="Back" onclick=" location = '../admin/productmanager.php';"/>
    <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>
    <form action="product.php?event=new" method="post">

    <label for="title">Name</label>
    <input type="text" name="title" id="title" value="<?=isset($_POST['title']) ? $_POST['title'] : ''  ?>"><br/>

    <label for="description">Description</label>
    <textarea name="description" id="description"><?=isset($_POST['description']) ? $_POST['description'] : ''  ?></textarea><br/>

    <label for="amount">Amount</label>
    <input type="text" name="amount" id="amount" value="<?=isset($_POST['amount']) ? $_POST['amount'] : '' ?>"><br/>

    <label for="available">Available</label>
    <input type="text" name="available" id="available" value="<?=isset($_POST['available']) ? $_POST['available'] : ""  ?>"><br/>

    <label for="image">Image</label>
    <input type="file" name="image" id="image"/><br/>

    <input type="hidden" name="picture" value="<?=isset($_POST['picture']) ? $_POST['picture'] : ""  ?>">
    <input type="hidden" name="new" value="1"/>
    <label style="color: red;"><?=$error_message?></label><br/>
    <input type="submit" value="New Product"/>
</form>
        </div>
    <?php
    drawFooter();
}
    else if($_GET['event'] == "remove"){
    if(!isset($_GET['id'])){
        header("Location: ../admin/productmanager.php");
    }else{
        drawHeader("Remove");
        ?>
        <div id="admin-content">
        <input type="button" value="Back" onclick=" location = '../admin/productmanager.php';"/>
        <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>

        <form action="product.php?event=remove&id=<?=$_GET['id']?>" method="post">
            <p>you sure remove product?</p>
            <input type="hidden" name="remove" value="1"/>
            <input type="submit" value="Yes"/>
            <input type="button" value="No" onclick=" location = '../admin/productmanager.php';"/>
        </form>
        </div>
    <?php
        drawFooter();
     }
}
}else {
    if(!isset($_GET['id'])){
        header("Location:../admin/productmanager.php");
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
        drawHeader("View");
        ?>
        <div id="admin-content">


    <input type="button" value="Back" onclick=" location = '../admin/productmanager.php';"/>
    <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>
    <input type="button" value="Modify" onclick=" location = '../admin/product.php?event=modify&id=<?=$_GET['id']?>';"/>
    <input type="button" value="Remove" onclick=" location = '../admin/product.php?event=remove&id=<?=$_GET['id']?>';"/>

        <br/>
        <img src="../<?=$picture?>" alt="<?=$title?>"/>


        <h2><?=$title?></h2>

        <label for="amount2">Amount</label>
        <p id="amount2"><?=$amount?> Bath.</p>

        <label for="available12">Available product</label>
        <p id="available12"><?=$available?></p>

        <label for="description2">Description</label>
        <p id="description2"><?=$description?></p>
     </div>
    <?php
    drawFooter();
    }
}
