<html>
    <head>
        <title>Alfikra</title>
        <link rel="stylesheet" href="includes/style.css">
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
		<script type="text/javascript" src="js/jquery.min.js"></script>
    </head>
    <body>
      <div id="cart">Welcome <?php
                    echo $_SESSION["username"];
                    echo ", <a href='logout.php' style='display:inline'>Logout</a>";
                    ?>
      </div>
        <div id="menu">
          <ul style="position:relative;top:20px;">
            <div id="logo"></div>
            <li<?php if ($thisPage=="index") echo " id=\"currentpage\""; ?>><a href="index.php">Home</a></li>
            <li<?php if ($thisPage=="history") echo " id=\"currentpage\""; ?>><a href="history.php">Ideas</a></li>
            <li<?php if ($thisPage=="shop") echo " id=\"currentpage\""; ?>><a href="shop.php">Shop</a></li>
            <li<?php if ($thisPage=="map") echo " id=\"currentpage\""; ?>><a href="map.php">Facilities</a></li>
            <li<?php if ($thisPage=="contact") echo " id=\"currentpage\""; ?>><a href="contact.php">Contact</a></li>
        </ul>
        </div>
		<div class="page-wrap">
