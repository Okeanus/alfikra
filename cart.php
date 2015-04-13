<?php 
session_start();
$thisPage="shop";
include "includes/header.php"; 
?> 

<div id="content">
	<div id="text1" style="padding-left:0px;">
            <?php
            $verbindung = mysql_connect("localhost", "jemline2", "121212");
            mysql_select_db("jemline2");
            $sum = 0;
            for($count = 0; $count < $_SESSION["counter"]; $count++)
            {
                if ($_SESSION["cart"][$count][1] > 0)
                {
                $abfrage = "SELECT * FROM product WHERE P_ID=" . $_SESSION["cart"][$count][0];
                $ergebnis = mysql_query($abfrage);
                $row = mysql_fetch_object($ergebnis);
                echo '<div class="row"><table><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Sum</th></tr>';
                echo '<tr style="vertical-align:middle;line-height:2;text-align:center;"><td style="padding-left:10px;" width=450>';
                echo substr($row->name, 0, 30) . "</td><td width=100>";
                echo $row->price . "&#8364";
                echo '</td><td width=100>'; 
                echo $_SESSION["cart"][$count][1];
                echo '</td><td>';
                echo $row->price * $_SESSION["cart"][$count][1] . "&#8364";
                echo '</td></tr></table></div>';
                $sum = $sum + $row->price * $_SESSION["cart"][$count][1];
                }
            }
            if($count > 0)
            {
                echo '<div><table style="text-align:center"><tr><td width=620></td><td>Total sum:</td><td>' . $sum . '&#8364</td></tr>'; 
                echo '<tr><td></td><td></td><td><a href="test.php"><input type="button" value="Checkout"></input></a></td></tr></table></div>';
            }
            else
            {
               echo "<div style='text-align:center;'>You haven't got any products in your cart. Please go into our shop.</div>" ;
            }
            
            ?>

	</div>
</div>


    
    
</body>



<?php 
include "includes/footer.php"; 
?> 
