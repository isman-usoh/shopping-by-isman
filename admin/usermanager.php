<?php
require_once("../include/databaseManager.class.php");
require_once("../include/admin_page.inc.php");
if(!isset($_SESSION['isAdmin'])){
    header("Location: ../login.php?redirect=".$_SERVER['REQUEST_URI']);
}else if($_SESSION['isAdmin'] == false){
    header("Location: ../index.php");
}
drawHeader("User Manager");
?>
<div id="admin-content">
<input type="button" value="Admin Page" onclick=" location = '../admin/index.php';"/>
<input type="button" value="Create User" onclick=" location = '../admin/user.php?event=new';"/>
<p style="color: green;">
    <?php
    if (isset($_GET['removesuccess'])) {
        echo "Remove user successful.";
    }
    if (isset($_GET['newsuccess'])) {
        echo "Create user successful.";
    }
    if(isset($_GET['modifysuccess'])){
        echo "Update user successful.";
    }
    ?>
</p>
<table  width="100%">
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>User Type</th>
    </tr>
    <?php
        DatabaseManager::openConnect();
        $queryString = "SELECT id, username, firstname, lastname, usertype
                        From users";
        $query = mysql_query($queryString);
        while($row = mysql_fetch_assoc($query)){
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td><a href='user.php?id=".$row['id']."'>".$row['username']."</a></td>";
            echo "<td>".$row['firstname']."</td>";
            echo "<td>".$row['lastname']."</td>";
            echo "<td>".($row['usertype'] == 0 ? "User" : "Admin" )."</td>";
            echo "</tr>";
        }
        DatabaseManager::closeConnect();
    ?>
</table>
</div>
<?php
drawFooter();
?>


