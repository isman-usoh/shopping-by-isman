<?php
require_once("../include/databaseManager.class.php");
require_once("../include/admin_page.inc.php");
if(!isset($_SESSION['isAdmin'])){
    header("Location: ../login.php?redirect=".$_SERVER['REQUEST_URI']);
}else if($_SESSION['isAdmin'] == false){
    header("Location: ../index.php");
}
drawHeader("Admin Page.");
drawFooter();
?>
