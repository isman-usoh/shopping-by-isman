<?php
require_once("include/databaseManager.class.php");
require_once("include/user_page.inc.php");
$login_success = false;
$error_message = "";

if(isset($_SESSION['userId'])){
    $login_success = true;
}

if (isset($_POST['logout'])) {
    unset($_SESSION['userId']);
    unset($_SESSION['firstname']);
    unset($_SESSION['lastname']);
    unset($_SESSION['isAdmin']);
    $login_success = false;
}
if (isset($_POST['login'])) {
    if (!(isset($_POST['username']) && strlen($_POST['username']) > 4 && isset($_POST['password']) && strlen($_POST['password']) > 4)) {
        $error_message = "username or password is short, try again.";
    } else {

        DatabaseManager::openConnect();
        $queryString = "SELECT id,firstname, lastname ,usertype
                    From users
                    Where username = '" . $_POST['username'] . "' and password = '" . $_POST['password'] . "'";
        $query = mysql_query($queryString);

        if (mysql_num_rows($query) == 1) {
            $user = mysql_fetch_assoc($query);
            $_SESSION['userId'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['isAdmin'] = $user['usertype'] == 0 ? false : true;

            $login_success = true;
        } else {
            $error_message = "username or password invalid, try again.";
        }
        DatabaseManager::closeConnect();
    }
}



if (!$login_success) {
    drawHeader("User Login");
    ?>
    <div class='form-dialog'>
    <h3>LogIn.</h3>
        <form action="login.php" method="post">
            <label for="username">Username</label>
            <input type="text" name="username" id="username"/>

            <label for="password">Password</label>
            <input type="password" name="password" id="password"/>

            <input type="hidden" name="login" value="1"/>
            <?php
                if(isset($_GET['redirect'])){
                    echo '<input type="hidden" name="redirect" value="'.$_GET['redirect'];'">';
                }
            ?>
            <label style="font-size:small;color: red;""><?=$error_message?></label><br/>
            <input type="submit" value="Login"/>
            <input type="reset" value="Reset"/>
            <br/><br/>

            <a href="register.php">Register.</a>
        </form>
    </div>
    <?php
    drawFooter();
} else {
    if(isset($_POST['redirect'])){
        header("'Location: ".$_POST['redirect']."'");
    }else{
        drawHeader("User LogOut");
        ?>
        <div class='form-dialog'>
            <h3>LogOut.</h3>
            <form action="login.php" method="post">
                <p>Welcome <?=$_SESSION['firstname']." ".$_SESSION['lastname']?></p><br/>
                <input type="hidden" name="logout" value="1"/>
                <input type="submit" value="Logout"/>
            </form>
        </div>
        <?php
        drawFooter();
    }
}
?>
