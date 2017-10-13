<?php
require_once("include/databaseManager.class.php");
require_once("include/user_page.inc.php");
$error_message = "";
$register_success = false;
$isRegister = true;
$date_valid = true;

if(isset($_SESSION['userId'])){
    $register_success = true;
    $isRegister = false;
}


if (isset($_POST['register'])) {
    if (strlen($_POST['username']) <= 4) {
        $error_message .= "  ชื่อผ้ใช้ส้นเกินไป";
        $date_valid = false;
    } else {
        DatabaseManager::openConnect();
        $queryString = "SELECT *
                        From users where username = '" . $_POST['username'] . "'";
        $query = mysql_query($queryString);
        if (mysql_num_rows($query) >= 1) {
            $date_valid = false;
            $error_message = "ไม่สามารถใช้ชื่อผใช้นี้ได้";
        }
        DatabaseManager::closeConnect();
    }

    if (strlen($_POST['password']) <= 4) {
        $error_message .= "  รหัสผ่านสั้นเกินไป";
        $date_valid = false;
    } else if ($_POST['password'] != $_POST['password1']) {
        $error_message .= "  รหัสผ่านไม่ตรงกัน";
        $date_valid = false;
    }
    if (strlen($_POST['firstname']) <= 4 || strlen($_POST['lastname']) <= 4) {
        $error_message .= "  ชื่อหรือนามสกุลสั้นเกินไป ";
        $date_valid = false;
    }

    if ($date_valid) {
        DatabaseManager::openConnect();
        $queryString = "INSERT INTO users
                        (id , username , password , firstname ,lastname ,usertype )VALUES
                        (NULL ,  '" . $_POST['username'] . "',  '" . $_POST['password'] . "',  '" . $_POST['firstname'] . "',  '" . $_POST['lastname'] . "',  '0');";

        $query = mysql_query($queryString);
        DatabaseManager::closeConnect();
        $register_success = true;

        $_SESSION['userId'] = mysql_insert_id();
        $_SESSION['firstname'] = $_POST['firstname'];
        $_SESSION['lastname'] = $_POST['lastname'];
        $_SESSION['isAdmin'] = false;
    }
}


if (!$register_success) {
    drawHeader("Register successful");
    echo "<div class='form-dialog'>";
    ?>
    <h3>Register.</h3>
    <form action="register.php" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" value="<?=isset($_POST['username']) ? $_POST['username'] : ""  ?>">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" >
        <label for="password1">Password again</label>
        <input type="password" name="password1" id="password1">

        <label for="firstname">Firstname</label>
        <input type="text" name="firstname" id="firstname" value="<?=isset($_POST['firstname']) ? $_POST['firstname'] : ""  ?>">

        <label for="lastname">Lastname</label>
        <input type="text" name="lastname" id="lastname" value="<?=isset($_POST['lastname']) ? $_POST['lastname'] : ""  ?>">


        <input type="hidden" name="register" value="1"/>
        <label style="font-size:small;color: red;"><?=$error_message?></label><br/>
        <input type="submit" value="Register"/>
        <input type="reset" value="Reset"/>
        <br/><br/>
        <a href="login.php">Login.</a>
    echo "</div>";
    drawFooter();
</form>
<?php
} else {
    drawHeader("Register successful");
    echo "<div class='form-dialog'>";
    echo '<h3>Register successful.</h3>';
    if($isRegister){
        echo '<p>Welcome '.$_SESSION["firstname"] .' '. $_SESSION["lastname"].'</p>';
    }
    echo '<input type="button" onclick="Location = \'profile.php?event=edit\'" value="EditProfile">';
    echo '<input type="button" onclick="Location = \'login.php\'" value="Logout"><br/>';
    echo "</div>";
    drawFooter();
}

?>
