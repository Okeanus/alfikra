<?php
$db = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");
if(isset($_POST['username']) && isset($_POST['password']))
{
$username = $_POST['username'];
$password = $_POST['password'];
$result=mysql_query("SELECT * FROM users WHERE username='$username' and password='$password'");
$rows = mysql_num_rows($result);

if($rows == 1)
{
$fetch = mysql_fetch_object($result);
echo $fetch->uid;
session_start();
$_SESSION['login_user']= $fetch->uid;
$_SESSION['username']= $fetch->username;
}

}
?>
