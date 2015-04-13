<?php
session_start();
$thisPage="shop";
if(isset($_POST["P_ID"])){
        
	
        $id = $_POST["P_ID"];
        $quant = $_POST["quant"];
        if ($quant > 0)
        {
        echo '<script>function notify(){alert("You have successfully ordered your product.");}document.onload = notify();</script>';
        }
        else
        {
        echo '<script>function notify(){alert("You cannot buy this product anymore.");}document.onload = notify();</script>';
        }
        for ($a = 0; $a < $_SESSION['counter']; $a++) {
            if ($_SESSION['cart'][$a][0] == $id) {
                $_SESSION['cart'][$a][1] += $quant;
                break;
            }
        }
        if ($a == $_SESSION["counter"]) {
            $_SESSION["cart"][$_SESSION["counter"]] = [$id, $_POST["quant"]];
            $_SESSION["counter"] = $_SESSION["counter"] + 1;
        }
}
else{
}
include "includes/header.php";
$verbindung = mysql_connect("localhost", "jemline2", "121212");
mysql_select_db("jemline2");
$result = $_GET['ProdId'];
$abfrage = "SELECT * FROM product WHERE P_ID=" . $result;
$ergebnis = mysql_query($abfrage);
$row = mysql_fetch_object($ergebnis);

?>

<div id="content">
    <div id="text1">
        <a href="shop.php"><button id="buyer" style="right:0px;"><div class="buybutton">Back</div></button></a>
        <table border="0">
            <tr>
                <td>
                    <div id="productviewimage" style="width:250px;height:250px;background-image:url('<?php echo $row->picture ?>')">
                    </div>
                </td>
                <td style="vertical-align:text-top">
                    <div id="prodname">
                        <b><?php echo $row->name ?></b><br><br><?php echo $row->info ?>
                        </td></tr></table>
                        <span style="position:absolute;margin-left:460px;margin-top:5px;">
                            <?php
                            $offset = 0;
                            for($count = 0; $count < $_SESSION["counter"]; $count++)
                            {
                                if ($row->P_ID == $_SESSION["cart"][$count][0])
                                {
                                    $offset = $_SESSION["cart"][$count][1];
                                }
                            
                            }
                            ?>
                            
                            Stock: <?php echo $row->stock-$offset; ?>
                        </span>
                        <span id="price">0.00&#8364;</span>
                        <form method="POST" action="product_view.php?ProdId=<?php $pid = $row->P_ID; echo $pid; ?>">
                            <input type="hidden"  name="P_ID" value="<?php echo $pid ?>"></input>
                            <input type="text" onchange="calc(this)" class="quantity" id="quanty" style="margin-top:10px;left:0px;position:relative;margin-left:550px;" name="quant" value="1"></input>
                            <button type="Submit" id="buyer" style="top:-10px;right:-100px;" name="buy"><div class="buybutton">Buy</div>
                            </button>
                        </form>
                    </div></div>
<script type="text/javascript">
            function calc()
            {
                var amount = 1;
                if(document.getElementById("quanty").value > <?php echo $row->stock-$offset ?>)
                {
                    amount = <?php echo $row->stock-$offset ?>;
                    document.getElementById("quanty").value = <?php echo $row->stock-$offset ?>;
                
                }
                else
                {
                    amount = document.getElementById("quanty").value;
                }
                var helpdigit = 0;
                if (<?php echo $row->stock-$offset ?> > 0)
                {
                    helpdigit = amount;
                }
                else
                {
                    helpdigit = 1;
                }
                document.getElementById("price").innerHTML = helpdigit * <?php echo $row->price ?> + "&#8364";
            }
            document.onload = calc();        
</script>
<?php 
include "includes/footer.php"; 
?> 
