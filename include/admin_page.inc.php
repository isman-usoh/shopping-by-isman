<?php
function drawHeader($title){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>www.Shopping.com | <?=$title?></title>
        <link rel="stylesheet" type="text/css" href="../styles/theme.css" />
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
    </head>
    <body>
        <div id="page">
            <div id="header">
                <div id="logo">Shopping.com</div>
                <div id="login">
                    <?php
                    if(!isset($_SESSION['firstname'])){
                    ?>
                        <a href="../login.php?redirect=<?=$_SERVER['REQUEST_URI']?>">Login</a> |
                        <a href="../register.php">Register</a>
                    <?php
                    }else{
                        echo 'Welcome '.$_SESSION["firstname"] .' '. $_SESSION["lastname"].'<br/>';
                        echo '<a href="../login.php">Logout</a>';
                    }
                    ?>
                </div>
            </div>
            <div style="height: 25px; width: 900px;">
                <div class="horizontal">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="usermanager.php">User Manager</a></li>
                        <li><a href="productmanager.php">Product Manager</a></li>
                        <li><a href="ordermanager.php">Order Manager</a></li>
                    </ul>
                </div>
            </div>
            <div id="content">
<?php
}
function drawFooter(){
?>
            </div>
            <div id="footer">
                <div id="copyright">
                @copyright shopping.com
                </div>
            </div>
        </div>
    </body>
</html>
<?php
}
?>