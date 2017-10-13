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
    if (strlen($_POST['username']) <= 4) {
        $error_message .= "  ชื่อผ้ใช้ส้นเกินไป";
        $date_valid = false;
    } else {
        if(isset($_POST['new'])){
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

        if($_GET['event'] == "modify"){
            DatabaseManager::openConnect();
            $queryString = "UPDATE  Users
                            SET  password = '".$_POST['password']."' ,
                            firstname = '".$_POST['firstname']."',
                            lastname = '".$_POST['lastname']."',
                            usertype = '".$_POST['usertype']."'
                            WHERE id = '".$_GET['id']."'";
            @mysql_query($queryString);
            DatabaseManager::closeConnect();
            header("Location: ../admin/usermanager.php?modifysuccess=".$_GET['id']);
        }else if($_GET['event'] == "new"){
            DatabaseManager::openConnect();
            $queryString = "INSERT INTO users
                        (id , username , password , firstname ,lastname ,usertype )VALUES
                        (NULL ,  '" . $_POST['username'] . "',  '" . $_POST['password'] . "',  '" . $_POST['firstname'] . "',  '" . $_POST['lastname'] . "',  '".$_POST['usertype']."');";

            $query = mysql_query($queryString);
            $id = mysql_insert_id();
            DatabaseManager::closeConnect();
            header("Location: ../admin/usermanager.php?newsuccess=".$id);
        }
    }
}
if (isset($_POST['remove'])){
    if($_GET['event'] == "remove"){
        DatabaseManager::openConnect();

        $queryString = "DELETE From Users where id = '".$_GET['id']."'";
        $query = mysql_query($queryString);
        DatabaseManager::closeConnect();
        header("Location: ../admin/usermanager.php?removesuccess=".$_GET['id']);
    }
}

if(isset($_GET['event'])){
    if($_GET['event'] == "modify"){
        if(!isset($_GET['id'])){
            header("Location: ../admin/usernager.php");
        }else{
            $username   = "";
            $password   = "";
            $firstname  = "";
            $lastname   = "";
            $userType   = 0;

            if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['usertype'])){
                $username   = $_POST['username'];
                $password   = $_POST['password'];
                $firstname  = $_POST['firstname'];
                $lastname   = $_POST['lastname'];
                $userType   = $_POST['usertype'];
            }else{
                DatabaseManager::openConnect();
                $queryString = "SELECT id, username, password, firstname, lastname, usertype
                             From users
                             where id = '".$_GET['id']."'";
                $query = mysql_query($queryString);

                $user = mysql_fetch_assoc($query);

                $username   = $user['username'];
                $password   = $user['password'];
                $firstname  = $user['firstname'];
                $lastname   = $user['lastname'];
                $userType   = $user['usertype'];
                DatabaseManager::closeConnect();
            }
            drawHeader("Edit");
            ?>
        <div id="admin-content">
        <input type="button" value="Back" onclick=" location = '../admin/usermanager.php';"/>
        <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>

        <form action="user.php?event=modify&id=<?=$_GET['id']?>" method="post">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" readonly="true" value="<?=$username?>"><br/>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" value="<?=$password?>"><br/>
                    <label for="password1">Password again</label>
                    <input type="password" name="password1" id="password1" value="<?=$password?>"><br/>

                    <label for="firstname">Firstname</label>
                    <input type="text" name="firstname" id="firstname" value="<?=$firstname?>"><br/>

                    <label for="lastname">Lastname</label>
                    <input type="text" name="lastname" id="lastname" value="<?=$lastname?>"><br/>


                    <label for="usertype">User Type</label>
                    <select name="usertype" id="usertype">
                        <option value="1" <?= $userType==1 ? 'selected="selected"' : ''?>>Admin</option>
                        <option value="0" <?= $userType==0 ? 'selected="selected"' : ''?>>User</option>
                    </select>

                    <input type="hidden" name="modify" value="1"/>
                    <label style="color: red;"><?=$error_message?></label><br/>
                    <input type="submit" value="Save"/>
                </form>
        </div>
        <?php
        drawFooter();
        }
    }
    else if($_GET['event'] == "new"){
        drawHeader("New");
        ?>
        <div id="admin-content">
        <input type="button" value="Back" onclick=" location = '../admin/usermanager.php';"/>
        <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>

        <form action="user.php?event=new" method="post">
            <label for="username1">Username</label>
            <input type="text" name="username" id="username1" value="<?=isset($_POST['username']) ? $_POST['username'] : ''  ?>"><br/>
            <label for="password11">Password</label>
            <input type="password" name="password" id="password11" ><br/>
            <label for="password12">Password again</label>
            <input type="password" name="password1" id="password12"><br/>

            <label for="firstname1">Firstname</label>
            <input type="text" name="firstname" id="firstname1" value="<?=isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>"><br/>


            <label for="lastname1">Lastname</label>
            <input type="text" name="lastname" id="lastname1" value="<?=isset($_POST['lastname']) ? $_POST['lastname'] : ""  ?>"><br/>

            <label for="usertype1">User Type</label>
            <select name="usertype" id="usertype1">
                <option value="1" >Admin</option>
                <option value="0" >User</option>
            </select>

            <input type="hidden" name="new" value="1"/>
            <label style="color: red;"><?=$error_message?></label><br/>
            <input type="submit" value="New User"/>
        </form>
        </div>
        <?php
        drawFooter();
    }
    else if($_GET['event'] == "remove"){
        if(!isset($_GET['id'])){
            header("Location: ../admin/usernager.php");
        }else{
            drawHeader("Delete");
        ?>
            <div id="admin-content">
        <input type="button" value="Back" onclick=" location = '../admin/usermanager.php';"/>
        <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>

        <form action="user.php?event=remove&id=<?=$_GET['id']?>" method="post">
            <p>you sure remove user?</p>
            <input type="hidden" name="remove" value="1"/>
            <input type="submit" value="Yes"/>
        </form>
          </div>
        <?php
        drawFooter();
        }
    }

}else {
    if(!isset($_GET['id'])){
        header("Location: ../admin/usernager.php");
    }else{
            DatabaseManager::openConnect();
            $queryString = "SELECT id, username, password, firstname, lastname, usertype
                         From users
                         where id = '".$_GET['id']."'";
            $query = mysql_query($queryString);

            $user = mysql_fetch_assoc($query);

            $username   = $user['username'];
            $password   = $user['password'];
            $firstname  = $user['firstname'];
            $lastname   = $user['lastname'];
            $userType   = $user['usertype'];
            DatabaseManager::closeConnect();
    drawHeader("View");
        ?>
     <div id="admin-content">
    <input type="button" value="Back" onclick=" location = '../admin/usermanager.php';"/>
    <input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>
    <input type="button" value="Modify" onclick=" location = '../admin/user.php?event=modify&id=<?=$_GET['id']?>';"/>
    <input type="button" value="Remove" onclick=" location = '../admin/user.php?event=remove&id=<?=$_GET['id']?>';"/>


        <h3>User Detail</h3>
        <div>Username : <?=$username?></div>
        <div>Firstname : <?=$firstname?></div>
        <div>Lastname : <?=$lastname?></div>
        <div>User Type : <?=$userType==1 ? "Admin" : "User"?></div>
    </div>
    <?php
    drawFooter();
    }
}
