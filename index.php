<?php
session_start();
$thisPage="index";
if(empty($_SESSION['login_user']))
{
header('Location: login.php');
}
include "includes/header.php";
?>

<div id="content">

</div>

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
            var canvas = loadCanvas("content");
            var context = canvas.getContext("2d");
            var bigBubble = {radius: 305, position:{x: window.innerWidth/2, y: 325}}; // only used as reference now
            var search = { search: false, input: "", hitsTitle: [], hitsAuthor: [] };
            var biggrd = context.createLinearGradient(0, 0, 325, 325);
            biggrd.addColorStop(0, "rgba(0, 128, 0, 0.3)");
            biggrd.addColorStop(1, "rgba(0, 128, 0, 0.9)");
            canvas.focus();

            $.ajax('getBubbles.php', {
                type: "GET",
                success: saveRecvBubbles
            });
            setInterval(physics, 33);
            setInterval(syncDB, 5000); // pull new bubbles from DB every 5sec

            function loadCanvas(id) {
                var canvas = document.createElement('canvas');
                var div = document.getElementById(id);

                canvas.id     = "CursorLayer";
                canvas.width  = window.innerWidth;
                canvas.height = window.innerHeight-90-30;
                canvas.style.zIndex   = 8;
                canvas.style.left   = 0;
                canvas.style.position = "absolute";
                canvas.addEventListener("mousedown", this.doMouseDown, false);
                window.addEventListener("keypress", this.doKeyPress, false);
                div.appendChild(canvas);

                return canvas;
            }
            function key2Char(key) {
                return String.fromCharCode(key);
            }
            function doKeyPress(event) {
                if (event.keyCode == 25)
                    rnd = !rnd;
                else if (isIdle && event.keyCode == 43)
                    bubbleList.push({radius: 75, position: {x: 75 + Math.floor(Math.random() * (canvas.width - 150)),
                                                        y: 75 + Math.floor(Math.random() * (canvas.height - 150))},
                                 title: "", author: "", messages: [], velocity: {x: 0, y: 0}});
                else if (isIdle) {
                    // Search
                    if (search.search != null)
                        clearTimeout(search.search);
                    search.search = setTimeout(function() {
                        search.input = "";
                        search.search = null;
                    }, 3000);
                    search.input += key2Char(event.keyCode);
                    search.hitsAuthor = [];
                    search.hitsTitle = [];
                    bubbleList.forEach(function(element, index) {
                        if (element.title.indexOf(search.input) > -1)
                            search.hitsTitle.push(index);
                        if (element.author.indexOf(search.input) > -1)
                            search.hitsAuthor.push(index);
                    });
                } else if (!isIdle)
                    if (event.keyCode == 17)
                        escapeBubble = true;
                    else if (!isTypingMeta && (event.keyCode >= 32 && event.keyCode < 127 ||
                            event.keyCode >= 128 && event.keyCode <= 255)) {
                        activeBubble.messages.push(key2Char(event.keyCode));
                        $.post('sendBubble.php', 
                           { bubbleId: activeBubble.bubbleId, title: activeBubble.title, author: activeBubble.author, messages: activeBubble.messages}
                          );
                        event.preventDefault();
                    }
            }
            function doMouseDown(event) {
                var mouse = {radius: 5, position:{x: event.x - canvas.offsetLeft - window.pageXOffset,
                    y: event.y - canvas.offsetTop + window.pageYOffset}}; // consider mouse a bubble

                if (isIdle) {
                    for (var i = 0; i < bubbleList.length; i++) {
                        if (!circleIsInside(mouse, bubbleList[i]))
                            continue;
                        isIdle = false;
                        activeBubble = bubbleList[i];
                        activeBubble.velocity.x = activeBubble.velocity.y = 0;
                        transition = {radius: 75, position: activeBubble.position};
                        break;
                    }
                } else {
                    if (input.title.draw == true || input.author.draw == true)
                        isTypingMeta = true;
                    else
                        isTypingMeta = false;
                }
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
                            bubble.messages = element.messages;
                        }
                        return found;
                    });
                    if (!found) {
                        element.radius = 75;
                        element.position = {x: 75 + Math.floor(Math.random() * (canvas.width - 150)),
                                                        y: 75 + Math.floor(Math.random() * (canvas.height - 150))};
                        element.velocity = {x: 0, y: 0};
                        bubbleList.push(element);
                    }
                });
            }
            function syncDB() {
                $.ajax('getBubbles.php', {
                    action: "GET",
                    success: saveRecvBubbles
                });
            }
            function physics() {
                bubbleList.forEach(function(element, index, array) {
                    if (element == activeBubble)
                        return; // continue
                    if (element.velocity.x == 0)
                        element.velocity.x = Math.floor(Math.random() * 3) - 1.5;
                    if (element.velocity.y == 0)
                        element.velocity.y = Math.floor(Math.random() * 3) - 1.5;
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
                    drawCircle(element.radius, element.position, rnd ? grd : biggrd, search.hitsAuthor.indexOf(index) > -1);
                    if (element.title != "") {
                        if (search.search != null && search.input != "" && search.hitsTitle.indexOf(index) > -1) {
                            context.fillStyle = "rgb(0, 0, 0)";
                            context.font = "24px Arial";
                        } else {
                            context.fillStyle = "rgb(42, 45, 47)";
                            context.font = "18px Arial";
                        }
                        var txt = element.title;
                        if (txt.length > 14)
                            txt = txt.substring(0, 11) + "...";
                        context.fillText(txt, element.position.x - context.measureText(txt).width / 2, element.position.y);
                    }
                });
                var txt;
                if (isIdle)
                    txt = "Press '+' to create a new Bubble!";
                else
                    txt = "Press 'Ctrl+Q' to exit a Bubble!";
                context.font = "26px Arial";
                context.fillStyle = "rgb(42, 45, 47)";
                context.fillText(txt, bigBubble.position.x - context.measureText(txt).width / 2, bigBubble.position.y - 15);
                if (search.search != null && search.input != "") {
                    txt = "Search: " + search.input;
                    context.font = "18px Arial";
                    context.fillText(txt, 15, 25);
                }
            }
            function destroyAllHumans() {
                input.title.draw = false;
                input.title.input = null;
                input.author.draw = false;
                input.author.input = null;
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
                    if (activeBubble.author == "") {
                        if (input.author.input == null)
                            input.author.input = new CanvasInput({
                                canvas: canvas,
                                x: bigBubble.position.x,
                                y: bigBubble.position.y - bigBubble.radius / 2,
                                fontSize: 14,
                                fontFamily: 'Arial',
                                fontColor: '#212121',
                                fontWeight: 'bold',
                                width: 180,
                                padding: 8,
                                borderWidth: 1,
                                borderColor: '#000',
                                borderRadius: 3,
                                boxShadow: '1px 1px 0px #fff',
                                innerShadow: '0px 0px 5px rgba(0, 0, 0, 0.5)',
                                placeHolder: 'Enter an author here...',
                                maxlength: 144,
                                onsubmit: onSubmit
                            });
                        input.author.draw = true;
                    }
                    if (input.title.draw)
                        input.title.input.render();
                    if (input.author.draw)
                        input.author.input.render();
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
                    activeBubble.messages.forEach(function(msg, i) {
                        var tmpX = posX + context.measureText(msg).width + 2;
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
            function onSubmit(e, self) {
                if (self == input.title.input && self._value != "") {
                    activeBubble.title = self._value;
                    input.title.draw = false;
                } else if (self == input.author.input && self._value != "") {
                    activeBubble.author = self._value;
                    input.author.draw = false;
                }
                if (activeBubble.title != "" && activeBubble.author != "") { // Save bubbles with title and author only
                    $.post('sendBubble.php', 
                           { bubbleId: null, title: activeBubble.title, author: activeBubble.author, messages: {}},
                            function(data) {
                                // Save Id into bubble object
                                activeBubble.bubbleId = data;
                            }
                          );
                }
                self.destroy();
            }
        </script>
</body>



<?php
include "includes/footer.php";
?>
