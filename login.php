<?php
session_start();
if(!empty($_SESSION))
{
  header('Location: index.php');
}
?>
<html>
    <head>
        <title>JemLine</title>
        <link rel="stylesheet" href="includes/style.css">
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.ui.shake.js"></script>
<script>
$(document).ready(function()
{

$('#login').click(function()
{
var username=$("#username").val();
var password=$("#password").val();
var dataString = 'username='+username+'&password='+password;
if($.trim(username).length>0 && $.trim(password).length>0)
{
$.ajax({
type: "POST",
url: "ajaxLogin.php",
data: dataString,
cache: false,
beforeSend: function(){ $("#login").val('Connecting...');},
success: function(data){
if(data)
{
$("body").load("index.php").hide().fadeIn(1500).delay(6000);

    if (typeof (history.pushState) != "undefined") {
        var obj = { Page: "index", Url: "index.php" };
        history.pushState(obj, obj.Page, obj.Url);
    } else {
        alert("Browser does not support HTML5.");
    }

}
else
{
//Shake animation effect.
$('#box').shake();
$("#login").val('Login')
$("#error").html("<span style='color:#cc0000'>Error:</span> Invalid username and password. ");
}
}
});

}
return false;
});

});
</script>
</head>
<div id="body">
<div id="box">
<form action="ajaxLogin.php" method="post">
Username
<input type="text" class="input"  id="username"/>
Password
<input type="password" class="input"  id="password"/>
<input type="submit" class="button button-primary" value="Log In" id="login"/>
<div id="error"></div>
</div>
</form>
</div>

</body>
</html>
