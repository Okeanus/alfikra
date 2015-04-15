<html>
    <head>
        <title>JemLine</title>
        <link rel="stylesheet" href="includes/style.css">
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="jspdf/libs/base64.js"></script>
		<script type="text/javascript" src="jspdf/libs/sprintf.js"></script>
		<script type="text/javascript" src="jspdf/jspdf.js"></script>
    </head>
    <body>
        <div id="menu">

        <ul style="position:relative;top:20px;">
            <div id="logo"></div>
            <a style="padding:0px;" href="cart.php"><div id="cart"></div></a>
            <div id="cart2">
            <?php
            // $_SESSION["cart"][$_SESSION["counter"]] = [$_POST["P_ID"], $_POST["quant"]];
                $c = 0;
                for ($a = 0; $a < $_SESSION['counter']; $a++) {
                    $c += $_SESSION['cart'][$a][1];
                }
                echo $c;

            ?></div>
            <li<?php if ($thisPage=="index") echo " id=\"currentpage\""; ?>><a href="index.php">Home</a></li>
            <li<?php if ($thisPage=="history") echo " id=\"currentpage\""; ?>><a href="history.php">Ideas</a></li>
            <li<?php if ($thisPage=="shop") echo " id=\"currentpage\""; ?>><a href="shop.php">Shop</a></li>
            <li<?php if ($thisPage=="map") echo " id=\"currentpage\""; ?>><a href="map.php">Facilities</a></li>
            <li<?php if ($thisPage=="contact") echo " id=\"currentpage\""; ?>><a href="contact.php">Contact</a></li>
        </ul>
        </div>
		<div class="page-wrap">
