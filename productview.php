<?php
session_start(); 
include "includes/header.php"; 
?> 

<div id="content">
    <div id="text1">
    bug
    <?php
        $verbindung = mysql_connect("localhost", "jemline2", "121212");
        mysql_select_db("jemline");
        $result = $_GET['ProdId'];
        $abfrage = "SELECT * FROM product WHERE P_ID=" . $result;
        $ergebnis = mysql_query($abfrage);
        $row = mysql_fetch_object($ergebnis);
        echo '<table border="0"><tr><td><div id="productviewimage" style="width:250px;height:250px;background-image:url(';
        echo "$row->picture";
        echo ')"></div></td><td style="vertical-align:text-top;"><div id="prodname">';
        echo "<b>$row->name</b><br>$row->info</td></tr></table></div>";      
        ?>    
    </div>
</div>


    
    
</body>



<?php 
include "includes/footer.php"; 
?> 
