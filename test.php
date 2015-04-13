<?php 
session_start();
$thisPage="shop";
include "includes/header.php"; 
if(isset($_POST["Mail"])){

$verbindung = mysql_connect("localhost", "JemLine", "121212");
mysql_select_db("jemline");
$abfrage = "INSERT INTO `jemline`.`order` (`Order_ID`, `email`, `name`, `adress`, `phone`, `status`) VALUES (NULL, '" . $_POST['Mail'] . "', '" . $_POST['Name'] . "','" . $_POST['Adress'] . "','" . $_POST['Phone'] . "', 1)";
$ergebnis = mysql_query($abfrage);
//$row = mysql_fetch_object($ergebnis);


$query2 = "SELECT Order_ID FROM `order` ORDER BY Order_ID DESC LIMIT 1";
$ergebnis = mysql_query($query2);
$row = mysql_fetch_object($ergebnis);
for($count = 0; $count < $_SESSION["counter"]; $count++)
{
$query3 = "INSERT INTO orderlist VALUES ('" . $row->Order_ID . "', '" . $_SESSION["cart"][$count][0] . "', '" . $_SESSION["cart"][$count][1] . "');";
$ergebnis = mysql_query($query3);

$query5 = "SELECT stock FROM `product` WHERE P_ID='" . $_SESSION["cart"][$count][0] . "' LIMIT 1" ;
$ergebnis = mysql_query($query5);
$row = mysql_fetch_object($ergebnis);

$query4 = "UPDATE product SET stock='" . ($row->stock  - $_SESSION["cart"][$count][1]) . "' WHERE P_ID='" . $_SESSION["cart"][$count][0] . "'";
$ergebnis = mysql_query($query4);
}
echo '<script type="text/javascript">'.
				"var doc = new jsPDF();".
				"doc.setProperties({".
					"title: 'Bill',".
					"subject: 'JemLine',".
					"author: 'JemLine',".
				"});".
				"doc.setFontSize(20);".
				"doc.text(20, 20, 'Order');".
				"doc.setFontSize(16);".
				'doc.text(20, 30, "Customer:  '. $_POST["Name"] . '");' .
				'doc.text(20, 40, "Mail: ' . $_POST["Mail"] . '");'.
				'doc.text(20, 50, "Adress: ' . $_POST["Adress"] . '");'.
				'doc.text(20, 60, "Phone: ' . $_POST["Phone"] . '");'.
				'doc.text(20, 80, "Order successfully completed!");'.
				
				"doc.output('datauri');".
	"</script>";   
session_destroy();
}

if(isset($_SESSION["cart"])){}
else{
        $_SESSION["cart"] = [[]];
        $_SESSION["counter"] = 0;
}

?> 

<div id="content">
	<form method="POST" action="test.php" style="padding-top:50px;">
		<p>Name:<br /><input name="Name" type="text" size="30" maxlength="30" id="name"></p>
		<p>e-Mail:<br /><input name="Mail" type="text" size="30" maxlength="50" id="mail" onblur="validateEmail(this);"></p>
		<p>Adress:<br /><input name="Adress" type="text" size="30" maxlength="60" id="adress"></p>
		<p>Phone:<br /><input name="Phone" type="text" size="30" maxlength="20" id="phone"></p>
		<p><input name="Submit" type="Submit" value="Submit" size="28"></p>
	</form>
</div>

</body>
<script>function validateEmail(emailField){
        var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;

        if (reg.test(emailField.value) == false) {
            alert('Invalid Email Address');
            return false;
        }

        return true;

}</script>
<?php 
include "includes/footer.php"; 
?> 