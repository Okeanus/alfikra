<?php 
session_start();
$thisPage="shop";
if(isset($_SESSION["cart"])){
    if(isset($_POST["P_ID"])){
        $_SESSION["P_ID"] = $_POST["quant"];
    }
        else
    {
    }
}
else
{
    $_SESSION['cart'] = [];
}


include "includes/header.php"; 
?> 

<div id="content">
    <div id="text1">
        <?php
        $verbindung = mysql_connect("localhost", "jemline2", "121212");
        mysql_select_db("jemline2");
        $abfrage = "SELECT * FROM product";
        $ergebnis = mysql_query($abfrage);
        while($row = mysql_fetch_object($ergebnis))
           {
           echo '<div class="showcase">';
           echo '<img src="';
           echo "$row->picture";
           echo '" width="200" style="margin-top:5px;margin-left:auto;margin-right:auto;display:block;"></img>';
           echo "<span class='title'>$row->name</span>";
           echo '<form method="GET" action="product_view.php"><button class="view" name="view" type="Submit"><div class="viewbutton">View</div></button>';
           echo '<input type="hidden" name="ProdId" value="';
           echo "$row->P_ID";
           echo '"></input>';
           echo '</form><form method="POST" action="product_view.php?ProdId=' . $row->P_ID . '"><input type="hidden" name="P_ID" value="' . $row->P_ID . '"></input>'; 
           if ($row->stock>0)
           {
           echo '<input type="hidden" name="quant" value=1></input>';
           }
           else
           {
           echo '<input type="hidden" name="quant" value=0></input>';
           }
           echo '<button type="Submit" class="buy" onClick="onbuy()" name="buy"><div class="buybutton">Buy</div></button></form></div>';
           }
        ?>    
    </div>
</div>


    
    
</body>



<?php 
include "includes/footer.php"; 
?> 