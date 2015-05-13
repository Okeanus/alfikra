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
</div>




</body>



<?php
include "includes/footer.php";
?>
