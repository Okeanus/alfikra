<?php
session_start();
if(!empty($_SESSION))
{
  header('Location: index.php');
}
?>
<html>
    <head>
        <title>Alfikra</title>
        <link rel="stylesheet" href="includes/style.css">
        <link rel="icon" href="img/favicon.ico" type="image/x-icon" />
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.ui.shake.js"></script>
<script>
$(document).ready(function()
{

$('#login').click(function()
{
  if ($("#username").val().length == 0)
  {
    $('#box').shake();
    $("#erroru").animate({'opacity':0.9},500, "swing");
    setTimeout(function(){ $("#erroru").animate({'opacity':0.0},500, "swing"); }, 3000);
  }
  else{
  if ($("#password").val().length == 0)
  {
    $('#box').shake();
    $("#errorp").animate({'opacity':0.9},500, "swing");
    setTimeout(function(){ $("#errorp").animate({'opacity':0.0},500, "swing"); }, 3000);
  }
  else
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
$("#error").animate({'opacity':0.9},500, "swing");
setTimeout(function(){ $("#error").animate({'opacity':0.0},500, "swing"); }, 3000);
}
}
});

}
}
}
return false;
});

});
</script>
<script>
$(document).ready(function(){

  function mf()
  {

    if ($("#bg").css("background-position-x") == '-100px'){
        $('#bg').animate({'background-position-x':100, 'background-position-y':0},10000, "swing");
    }
    else
    {
      $('#bg').animate({'background-position-x':-100, 'background-position-y':0},10000, "swing");
    }
    $.ajax({
      url:'tumblr',
      success: function (data){
        var array = data.split(",");
        $('#bg').css('background-image', 'url(' + array[Math.floor((Math.random() * 20) + 1)] + ')');
      }
    })
  }

  mf();
  setInterval(function(){ mf(); }, 10000);
  });

</script>
<script>
var set = 0
function register()
{
  if (set == 0)
  {
    set = 1
    var newdiv = document.createElement('div');
    newdiv.innerHTML = "<input type='text' id='email' size='15' class='input' placeholder='E-Mail' name='email'/><br>";
    document.getElementById("inputs").appendChild(newdiv);
  }
  else
  {
    if ($("#username").val().length == 0)
    {
      $('#box').shake();
      $("#erroru").animate({'opacity':0.9},500, "swing");
      setTimeout(function(){ $("#erroru").animate({'opacity':0.0},500, "swing"); }, 3000);
    }
    else{
    if ($("#password").val().length == 0)
    {
      $('#box').shake();
      $("#errorp").animate({'opacity':0.9},500, "swing");
      setTimeout(function(){ $("#errorp").animate({'opacity':0.0},500, "swing"); }, 3000);
    }
    else{
    if ($("#email").val().length == 0)
    {
      $('#box').shake();
      $("#errore").animate({'opacity':0.9},500, "swing");
      setTimeout(function(){ $("#errore").animate({'opacity':0.0},500, "swing"); }, 3000);
    }
    else
    {
      var re = /\S+@\S+\.\S+/;
      if (re.test($("#email").val()) == false)
      {
        $('#box').shake();
        $("#errore").animate({'opacity':0.9},500, "swing");
        setTimeout(function(){ $("#errore").animate({'opacity':0.0},500, "swing"); }, 3000);
      }
      else
      {
        var dataString = 'username='+$("#username").val()+'&password='+$("#password").val() + '&email=' + $("#email").val();
        $.ajax({
        type: "POST",
        url: "ajaxRegister.php",
        data: dataString,
        cache: false,

        success: function(data){
        if(data.indexOf('User') == -1)
        {
            $("body").load("howto.php").hide().fadeIn(1500).delay(6000);

            if (typeof (history.pushState) != "undefined") {
                var obj = { Page: "howto", Url: "howto.php" };
                history.pushState(obj, obj.Page, obj.Url);
            } else {
                alert("Browser does not support HTML5.");
            }
        }
        else
        {
          $("#errorus").animate({'opacity':0.9},500, "swing");
          setTimeout(function(){ $("#errorus").animate({'opacity':0.0},500, "swing"); }, 3000);
        }
      }
    });
  }
}
}
}
}
}
</script>
</head>
<body>

  <div id="bg">
  </div>
  <div id="logologin">
  </div>
  <div id="quote">
    <big><big><big>&ldquo;</big></big>Everything begins with an idea. <big><big>&rdquo;</big></big></big>
    <div id="author" style="text-align:center;margin-left:170px;margin-top: 10px;">
      <i>Earl Nightingale</i>
    </div>
  </div>
<div id="box">
<form id="log" action="ajaxLogin.php" method="post">
  <div id="inputs">
<input type="text" size="15" class="input"  placeholder="Username" id="username"/><br>
<input type="password" size="15" class="input" placeholder="Password"  id="password"/><br>
</div>
<div>
<div style="float:left; width:50%;">
<input  style="margin-right:2;" type="submit" class="button button-primary" value="Login" id="login"/>
</div>
<div style="float:right; width:50%;">
<input style="margin-left:2;background-color:#37BEF8" type="button" onclick="register();" class="button button-primary" value="Register" id="registera"/>
</div>
</div>
</form>
</div>
<div id="error"> Invalid username or password!
</div>
<div id="erroru"> Type your username in!
</div>
<div id="errorp"> Type your password in!
</div>
<div id="errore"> Check your email!
</div>
<div id="errorus"> Username already in use!
</div>

</body>
</html>
