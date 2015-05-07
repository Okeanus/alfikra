<?php
$db = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");

$title = $_POST['title'];
$author = $_POST['author'];

if (!isset($_POST['bubbleId']))
{
  $datstring =  "INSERT INTO bubbles VALUES(NULL, '" . $title . "', '" . $author . "','')";
  if (mysql_query($datstring))
  {
  $result=mysql_query("SELECT * FROM bubbles WHERE title='" . $title . "' and author='" .  $author . "' and messages=''");
  $rows = mysql_num_rows($result);
  $fetch = mysql_fetch_object($result);
  echo $fetch->bubbleId;
  }
}
else
{
  $messages = $_POST['messages'];
    $bubbleId = $_POST['bubbleId'];
    mysql_query($datstring =  "UPDATE bubbles SET title = '" . $title . "' , author = '" . $author . "', messages='" .$messages . "' WHERE bubbleId = '" . $bubbleId . "'");
}
?>
