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
var set = 0
function register()
{
  if (set == 0)
  {
    set = 1
    var newdiv = document.createElement('div');
    newdiv.innerHTML = "<input type='text' id='email' size='15' class='input' placeholder='E-Mail' name='email'/><br>";
    document.getElementById("inputs").appendChild(newdiv);
    $("#login").attr("onclick", "register()");
    $("#login").attr("id", "none");
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
  <script src="js/Canvas-Input.js"></script>
  <script type="text/javascript">
              var requestAnimationFrame = window.requestAnimationFrame ||
                  window.mozRequestAnimationFrame ||
                  window.webkitRequestAnimationFrame ||
                  window.msRequestAnimationFrame;
              var inputBuffer = [];
              var isIdle = true;
              var rnd = true;
              var escapeBubble = false;
              var isTypingMeta = false;
              var transition;
              var activeBubble;
              var bubbleList = [];
              var rndList = [];
              var input = {id: null, title: {input: null, draw: false}, author: {input: null, draw: false}};
              var canvas = loadCanvas("bg");
              var context = canvas.getContext("2d");
              var bigBubble = {radius: 305, position:{x: window.innerWidth/2, y: 325}}; // only used as reference now
              var search = { search: false, input: "", hits: [] };
              var biggrd = context.createLinearGradient(0, 0, 325, 325);
              biggrd.addColorStop(0, "rgba(0, 128, 0, 0.3)");
              biggrd.addColorStop(1, "rgba(0, 128, 0, 0.9)");
              canvas.focus();

              setInterval(physics, 50);

              function loadCanvas(id) {
                  var canvas = document.createElement('canvas');
                  var div = document.getElementById(id);

                  canvas.id     = "CursorLayer";
                  canvas.width  = window.innerWidth;
                  canvas.height = window.innerHeight;
                  canvas.style.zIndex   = 8;
                  canvas.style.left   = 0;
                  canvas.style.top   = 0;
                  canvas.style.position = "absolute";
                  canvas.addEventListener("mousedown", this.doMouseDown, false);
                  window.addEventListener("keypress", this.doKeyPress, false);
                  div.appendChild(canvas);

                  return canvas;
              }
              function key2Char(key) {
                  return String.fromCharCode(key);
              }

              function gen() {
                      bubbleList.push({radius: 75, position: {x: 75 + Math.floor(Math.random() * (canvas.width - 150)),
                                                          y: 75 + Math.floor(Math.random() * (canvas.height - 150))},
                                   title: "", author: "", messages: [], velocity: {x: 0, y: 0}});

              }

              function distance(a, b) {
                  return Math.sqrt(Math.pow(b.position.x - a.position.x, 2) + Math.pow(b.position.y - a.position.y, 2));
              }
              function circleIsInside(a, b) {
                  var d = distance(a, b);
                  if (d == 0 || d < Math.abs(Math.abs(a.radius) - Math.abs(b.radius)))
                      return true;
                  return false;
              }
              function circleCollide(a, b) {
                  return circleIsInside(a, b) || !(distance(a, b) > Math.abs(a.radius) + Math.abs(b.radius));
              }
              function saveRecvBubbles(data) {
                  var json = JSON.parse(data);
                  json.forEach(function(element) {
                      var found = false;
                      bubbleList.some(function(bubble, index) {
                          if (element.bubbleId == bubble.bubbleId) {
                              found = true;
                              bubble.title = element.title;
                              bubble.author = element.author;
                              bubble.messages = element.messages.split('');
                          }
                          return found;
                      });
                      if (!found) {
                          element.radius = 75;
                          element.position = {x: 75 + Math.floor(Math.random() * (canvas.width - 150)),
                                                          y: 75 + Math.floor(Math.random() * (canvas.height - 150))};
                          element.velocity = {x: 0, y: 0};
                          element.messages = element.messages.split('');
                          bubbleList.push(element);
                      }
                  });
              }
              function syncDB() {
              }
              function physics() {
                  bubbleList.forEach(function(element, index, array) {
                      if (element == activeBubble)
                          return; // continue
                      if (element.velocity.x == 0)
                          element.velocity.x = Math.floor(Math.random() * 1.2) - .5;
                      if (element.velocity.y == 0)
                          element.velocity.y = Math.floor(Math.random() * 1.2) - .5;
                      var tmp = {x: element.position.x + element.velocity.x,
                                 y: element.position.y + element.velocity.y};
                      if (tmp.x - element.radius <= 0 || tmp.x + element.radius >= canvas.width)
                          element.velocity.x *= -1;
                      if (tmp.y - element.radius <= 0 || tmp.y + element.radius >= canvas.height)
                          element.velocity.y *= -1;
                      element.position.x += element.velocity.x;
                      element.position.y += element.velocity.y;
                  });
                  requestAnimationFrame(drawRoutine);
                  if (!isIdle)
                      requestAnimationFrame(drawInnerBubble);
              }
              function drawCircle(radius, position, gradient, hlAuth) {
                  // draw the circle
                  context.beginPath();

                  context.arc(position.x, position.y, radius, 0, Math.PI * 2, false);
                  context.closePath();
                  if (hlAuth == true) {
                      context.strokeStyle = "red";
                      context.lineWidth = 3;
                  } else {
                      context.strokeStyle = "grey";
                      context.lineWidth = 1;
                  }
                  context.stroke();

                  // color in the circle
                  context.fillStyle = gradient;
                  context.fill();
              }
              function drawRoutine() {
                  // Clear the Canvas
                  context.clearRect(0, 0, canvas.width, canvas.height);
                  var hitList = [];
                  bubbleList.forEach(function(element, index) {
                      if (!isIdle && element == activeBubble)
                          return; // continue
                      if (rnd)  {
                          var grd;
                          if (rndList[index] == null)  {
                              grd = context.createLinearGradient(0, 0, 800, 800);
                              grd.addColorStop(0, "rgba(" + Math.floor(Math.random() * 255) + ", " + Math.floor(Math.random() * 255) +
                                               ", " + Math.floor(Math.random() * 255) + ", " + Math.random() + ")");
                              grd.addColorStop(1, "rgba(" + Math.floor(Math.random() * 255) + ", " + Math.floor(Math.random() * 255) +
                                               ", " + Math.floor(Math.random() * 255) + ", " + (Math.random() / 2 + 0.5) + ")");
                              rndList[index] = grd;
                          } else
                              grd = rndList[index];
                      }
                      if (search.hits.indexOf(index) > -1) {
                          hitList.push(element);
                          return;
                      }
                      drawCircle(element.radius, element.position, rnd ? grd : biggrd);
                      if (element.title != "") {
                          context.fillStyle = "rgb(42, 45, 47)";
                          context.font = "20px Arial";
                          var txt = element.title;
                          if (txt.length > 14)
                              txt = txt.substring(0, 11) + "...";
                          context.fillText(txt, element.position.x - context.measureText(txt).width / 2, element.position.y);
                      }
                  });
                  // Draw Late to put it into front
                  hitList.forEach(function(element, index) {
                      if (!isIdle && element == activeBubble)
                          return; // continue
                      if (rnd)  {
                          var grd;
                          if (rndList[search.hits[index]] == null)  {
                              grd = context.createLinearGradient(0, 0, 800, 800);
                              grd.addColorStop(0, "rgba(" + Math.floor(Math.random() * 255) + ", " + Math.floor(Math.random() * 255) +
                                               ", " + Math.floor(Math.random() * 255) + ", " + Math.random() + ")");
                              grd.addColorStop(1, "rgba(" + Math.floor(Math.random() * 255) + ", " + Math.floor(Math.random() * 255) +
                                               ", " + Math.floor(Math.random() * 255) + ", " + (Math.random() / 2 + 0.5) + ")");
                              rndList[search.hits[index]] = grd;
                          } else
                              grd = rndList[search.hits[index]];
                      }
                      drawCircle(element.radius, element.position, rnd ? grd : biggrd, true);
                      if (element.title != "") {
                          context.fillStyle = "rgb(0, 0, 0)";
                          context.font = "24px Arial";
                          var txt = element.title;
                          if (txt.length > 14)
                              txt = txt.substring(0, 11) + "...";
                          context.fillText(txt, element.position.x - context.measureText(txt).width / 2, element.position.y);
                      }
                  });
              }
              function destroyAllHumans() {
                  input.title.draw = false;
                  input.title.input = null;
              }
              function drawTransition(cb) {
                  drawCircle(transition.radius, transition.position, rnd ? rndList[bubbleList.indexOf(activeBubble)] : biggrd);
                  if (escapeBubble) {
                      if (transition.radius > 75)
                          transition.radius -= 10;
                      else {
                          isIdle = true;
                          escapeBubble = false;
                          destroyAllHumans();
                          activeBubble.velocity.x = activeBubble.velocity.y = 0;
                          activeBubble = null;
                      }
                  } else {
                      if (activeBubble.velocity.x == 0 && transition.position.x != bigBubble.position.x)
                          activeBubble.velocity.x = (bigBubble.position.x - activeBubble.position.x) / 12;
                      if (activeBubble.velocity.y == 0 && transition.position.y != bigBubble.position.y)
                          activeBubble.velocity.y = (bigBubble.position.y - activeBubble.position.y) / 12;
                      if (Math.abs(bigBubble.position.x - transition.position.x) > 5)
                          transition.position.x += activeBubble.velocity.x;
                      else
                          transition.position.x = bigBubble.position.x;
                      if (Math.abs(bigBubble.position.y - transition.position.y) > 5)
                          transition.position.y += activeBubble.velocity.y;
                      else
                          transition.position.y = bigBubble.position.y;
                      if (transition.position.x == bigBubble.position.x && transition.position.y == bigBubble.position.y) {
                          if (transition.radius < bigBubble.radius)
                              transition.radius += 10;
                          else // done transitioning
                              cb();
                      }
                  }
              }
              function drawInnerBubble() {
                  // The "inner" bubble
                  drawTransition(function() {
                      if (activeBubble.title == "") {
                          if (input.title.input == null)
                              input.title.input = new CanvasInput({
                                  canvas: canvas,
                                  x: bigBubble.position.x - 240 / 2,
                                  y: bigBubble.position.y - bigBubble.radius / 1.5 - 20,
                                  fontSize: 14,
                                  fontFamily: 'Arial',
                                  fontColor: '#212121',
                                  fontWeight: 'bold',
                                  width: 240,
                                  padding: 8,
                                  borderWidth: 1,
                                  borderColor: '#000',
                                  borderRadius: 3,
                                  boxShadow: '1px 1px 0px #fff',
                                  innerShadow: '0px 0px 5px rgba(0, 0, 0, 0.5)',
                                  placeHolder: 'Enter a title here...',
                                  maxlength: 144,
                                  onsubmit: onSubmit
                              });
                          input.title.draw = true;
                      }
                      if (input.title.draw)
                          input.title.input.render();
                      context.fillStyle = "rgb(0, 0, 0)";
                      context.font = "20px Verdana";

                      context.fillText(activeBubble.title, bigBubble.position.x - (activeBubble.title.length / 2 * 16), bigBubble.position.y - bigBubble.radius / 1.5);
                      context.fillText("Author: ", bigBubble.position.x - 200, bigBubble.position.y - bigBubble.radius / 2 + 20);
                      context.fillText(activeBubble.author, bigBubble.position.x, bigBubble.position.y - bigBubble.radius / 2 + 20);
                      context.fillStyle = "rgba(255, 255, 45, 0.54)";
                      context.fillRect(bigBubble.position.x - bigBubble.radius + 75, bigBubble.position.y, bigBubble.radius * 2 - 150, bigBubble.radius - 150);
                      context.fillStyle = "rgb(0, 0, 0)";
                      context.font = "14px Verdana";
                      var posX = bigBubble.position.x - bigBubble.radius + 75;
                      var lineCount = 0;
                      if (activeBubble.messages.length)
                          activeBubble.messages.forEach(function(msg, i) {
                              var tmpX = posX + context.measureText(msg).width + 1;
                              if (tmpX >= bigBubble.position.x - bigBubble.radius + 75 + (bigBubble.radius * 2 - 150) - context.measureText(msg).width) {
                                  lineCount++;
                                  posX = bigBubble.position.x - bigBubble.radius + 75;
                              } else
                                  posX = tmpX;
                              var posY = bigBubble.position.y + 16 + 16 * lineCount;
                              if (lineCount >= 9)
                                  return;
                              context.fillText(msg, posX, posY);
                          });
                  });
              }
              for (i = 0; i < 10; i++) {
                  gen();
                }
              function onSubmit(e, self) {
                  if (self == input.title.input && self._value != "") {
                      activeBubble.title = self._value;
                      input.title.draw = false;
                  }
                  if (activeBubble.title != "") { // Save bubbles with title and author only
                      $.post('sendBubble.php',
                             { title: activeBubble.title, author: document.getElementById("usernam3").innerHTML },
                              function(data) {
                                  // Save Id into bubble object
                                  activeBubble.bubbleId = data;
                              }
                            );
                  }
                  self.destroy();
              }
          </script>
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
