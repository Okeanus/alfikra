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
            var escapeBubble = false;
            var isTypingMeta = false;
            var transition;
            var activeBubble;
            var bubbleList = [];
            var input = {title: {input: null, draw: false}, author: {input: null, draw: false}};
            var canvas = loadCanvas("content");
            var context = canvas.getContext("2d");
            var bigBubble = {radius: 305, position:{x: 325, y: 325}};
            var biggrd = context.createLinearGradient(0, 0, 325, 325);
            biggrd.addColorStop(0, "rgba(0, 128, 0, 0.3)");
            biggrd.addColorStop(1, "rgba(0, 128, 0, 0.9)");

            for (var i = 0; i < 10; i++)
                bubbleList.push({radius: 75, position: {x: 300 + i * Math.floor(Math.random() * 4000) % 250, 
                                                        y: 300 + i * Math.floor(Math.random() * 4000) % 250}, 
                                 title: "", author: "", messages: [], velocity: {x: 0, y: 0}});
            setInterval(physics, 33);

            function loadCanvas(id) {
                var canvas = document.createElement('canvas');
                var div = document.getElementById(id); 

                canvas.id     = "CursorLayer";
                canvas.width  = 800;
                canvas.height = 800;
                canvas.style.zIndex   = 8;
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
                if (!isIdle)
                    if (event.keyCode == 17)
                        escapeBubble = true;
                    else if (!isTypingMeta && (event.keyCode >= 32 && event.keyCode < 127 ||
                            event.keyCode >= 128 && event.keyCode <= 255)) {
                        activeBubble.messages.push(key2Char(event.keyCode));
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
            function physics() {
                if (isIdle) {
                    bubbleList.forEach(function(element, index, array) {
                        if (element.velocity.x == 0)
                            element.velocity.x = Math.floor(Math.random() * 5) - 2.5;
                        if (element.velocity.y == 0)
                            element.velocity.y = Math.floor(Math.random() * 5) - 2.5;
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
                } else
                    requestAnimationFrame(drawInnerBubble);
            }
            function drawCircle(radius, position, gradient) {
                // draw the circle
                context.beginPath();
                 
                context.arc(position.x, position.y, radius, 0, Math.PI * 2, false);
                context.closePath();
                 
                // color in the circle
                context.fillStyle = gradient;
                context.fill();
            }
            function drawRoutine() {
                // Clear the Canvas
                context.clearRect(0, 0, canvas.width, canvas.height); 

                // Draw the small bubbles
                bubbleList.forEach(function(element) {
                    drawCircle(element.radius, element.position, biggrd);
                    if (element.title != "") {
                        context.fillStyle = "rgb(0, 0, 0)";
                        context.font = "14px Arial";
                        var txt = element.title;
                        if (txt.length > 14)
                            txt = txt.substring(0, 11) + "...";
                        context.fillText(txt, element.position.x - (txt.length * 5), element.position.y);
                    }
                });
            }
            function destroyAllHumans() {
                input.title.draw = false;
                input.title.input = null;
                input.author.draw = false;
                input.author.input = null;
            }
            function drawTransition(cb) {
                drawCircle(transition.radius, transition.position, biggrd);
                if (escapeBubble) {
                    if (transition.radius > 75)
                        transition.radius -= 10;
                    else {
                        isIdle = true;
                        escapeBubble = false;
                        destroyAllHumans();
                    }
                } else {
                    if (transition.position.x != bigBubble.position.x)
                        transition.position.x = Math.abs(Math.abs(bigBubble.position.x) - Math.abs(transition.position.x)) < 5 ? bigBubble.position.x : 
                            transition.position.x > bigBubble.position.x ? transition.position.x - 5 : transition.position.x + 5;
                    if (transition.position.y != bigBubble.position.y)
                        transition.position.y = Math.abs(Math.abs(bigBubble.position.y) - Math.abs(transition.position.y)) < 5 ? bigBubble.position.y : 
                            transition.position.y > bigBubble.position.y ? transition.position.y - 5 : transition.position.y + 5;
                    if (transition.position.x == bigBubble.position.x && transition.position.y == bigBubble.position.y) {
                        if (transition.radius < bigBubble.radius)
                            transition.radius += 10;
                        else // done transitioning
                            cb();
                    }
                }
            }
            function drawInnerBubble() {
                // Clear the Canvas
                context.clearRect(0, 0, canvas.width, canvas.height); 
                
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
                                placeHolder: 'Enter a message here...',
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
                        var posY = bigBubble.position.y + 16 + 16 * lineCount
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
                self.destroy();
            }
        </script>
</body>



<?php
include "includes/footer.php";
?>
