<?php
$db = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");

$t = json_decode(file_get_contents('php://input'));

if (strcmp($t->id, "null") == 0)
{
  $datstring =  "INSERT INTO bubbles VALUES(NULL, '" . $t->title . "', '" . $t->author . "','" . $t->message . "')";
  if (mysql_query($datstring))
  {
  $result=mysql_query("SELECT * FROM bubbles WHERE title='" . $t->title . "' and author='" .  $t->author . "' and message='" . $t->message . "'");
  $rows = mysql_num_rows($result);
  $fetch = mysql_fetch_object($result);
  echo $fetch->bubbleId;
}
}
else
{
  echo "fuck";
}
?>
