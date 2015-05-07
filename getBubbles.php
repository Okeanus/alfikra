<?php
$db = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");

$result=mysql_query("SELECT * FROM bubbles");
$rows = mysql_num_rows($result);


while ($row = mysql_fetch_object($result)) {
    echo json_encode($row);
}
?>
