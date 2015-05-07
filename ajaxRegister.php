<?php
$db = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");

if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
{
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  $result=mysql_query("SELECT * FROM users WHERE username='$username'");
  $rows = mysql_num_rows($result);
  if ($rows)
  {
    echo "User already in use";
  }
  else
  {
  $datstring =  "INSERT INTO users VALUES(" . "NULL, '" . $username . "', '" . $password . "','" . $email . "', NULL, NULL)";
  if (mysql_query($datstring))
  {
    $result=mysql_query("SELECT * FROM users WHERE username='$username' and password='$password'");
    $rows = mysql_num_rows($result);
    $fetch = mysql_fetch_object($result);
    echo $fetch->uid;
    session_start();
    $_SESSION['login_user']= $fetch->uid;
    $_SESSION['username']= $fetch->username;
  }
  else{}
  }
}

?>
