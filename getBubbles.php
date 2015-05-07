<?php
$db = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");

$result=mysql_query("SELECT * FROM bubbles");
$rows = mysql_num_rows($result);

echo "[";
$id = 0;
while ($row = mysql_fetch_object($result)) {
    if ($id > 0)
        echo ",";
    //echo '"' . $id . '":';
    echo json_encode($row);
    $id = $id+1;
}
echo "]";
?>
