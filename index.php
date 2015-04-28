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

<script src="js/CanvasInput.min.js"></script>
<script type="text/javascript">
            var requestAnimationFrame = window.requestAnimationFrame || 
                window.mozRequestAnimationFrame || 
                window.webkitRequestAnimationFrame || 
                window.msRequestAnimationFrame;
            var inputBuffer = [];
            var isIdle = true;
            var escapeBubble = false;
            var isMoving = true;
            var isTypingMeta = false;
            var transition;
            var activeBubble;
            var bubbleList = [];
            var input = {title: null, author: null};
            var canvas = loadCanvas("content");
            var context = canvas.getContext("2d");
            var bigBubble = {radius: 305, position:{x: 325, y: 325}, q: e, D: 0.005};
            var e0 = 8.85418781762E-12;
            var e = 1.602176565E-19;
            // The two types of gradients
            var biggrd = context.createLinearGradient(0, 0, 325, 325);
            biggrd.addColorStop(0, "rgba(0, 128, 0, 0.3)");
            biggrd.addColorStop(1, "rgba(0, 128, 0, 0.9)");
            var smallgrd = context.createLinearGradient(0, 0, 75, 75);
            smallgrd.addColorStop(0, "rgba(255, 185, 0, 0.3");
            smallgrd.addColorStop(1, "rgba(255, 185, 0, 0.9");

            for (var i = 0; i < 10; i++)
                bubbleList.push({radius: 75, position:{x: 300 + i * Math.random() % 100, y: 300 + i * Math.random() % 100}, 
                                 q: e * 10E+14, title: "", author: "", messages: []});
            setInterval(physics, 33);

            function loadCanvas(id) {
                var canvas = document.createElement('canvas');
                var div = document.getElementById(id); 

                canvas.id     = "CursorLayer";
                canvas.width  = 800;
                canvas.height = 600;
                canvas.style.zIndex   = 8;
                canvas.style.position = "absolute";
                canvas.addEventListener("mousedown", this.doMouseDown, false);
                window.addEventListener("keypress", this.doKeyPress, false);
                div.appendChild(canvas);

                return canvas;
            }
            function doKeyPress(event) {
                if (!isIdle)
                    if (event.keyCode == 17)
                        escapeBubble = true;
                    else {
                           
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
                        transition = {radius: 75, position:activeBubble.position};
                        break;
                    }
                } else {
                    
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
                    if (isMoving) {
                        // Physics, only when the user is idle
                        var movementVectors = [];
                        var doubleBufferList = [];
                        var mov = false;
                        bubbleList.forEach(function(element, index, array) {
                            var vector = {x: 0, y: 0};
                            array.forEach(function(other, otherindex) {
                                if (index != otherindex) {
                                    if (element.position.x != other.position.x)
                                        vector.x += (element.q * other.q) / (4 * Math.PI * e0 * (element.position.x - other.position.x));
                                    if (element.position.y != other.position.y)
                                        vector.y += (element.q * other.q) / (4 * Math.PI * e0 * (element.position.y - other.position.y));
                                    if (circleCollide(element, other)) {
                                        vector.x *= 1.1;
                                        vector.y *= 1.1;
                                    }
                                }
                            });
                            vector.x += bigBubble.D * bubbleList.length * (bigBubble.position.x - element.position.x);
                            vector.y += bigBubble.D * bubbleList.length * (bigBubble.position.y - element.position.y);

                            vector.x = vector.x > 25 ? 25 : vector.x < -25 ? -25 : vector.x;
                            vector.y = vector.y > 25 ? 25 : vector.y < -25 ? -25 : vector.y;
                            movementVectors[index] = vector;
                        });
                        bubbleList.forEach(function(element, index) {
                            var pos = {x: element.position.x + movementVectors[index].x, 
                                       y: element.position.y + movementVectors[index].y};
                            
                            if (pos.x < element.radius || pos.x > canvas.width - element.radius)
                                pos.x -= movementVectors[index].x * 1.1;
                            if (pos.y < element.radius || pos.y > canvas.height - element.radius)
                                pos.y -= movementVectors[index].y * 1.1;
                            if (movementVectors[index].x < 2 && movementVectors[index].x > -2 && 
                                movementVectors[index].y < 2 && movementVectors[index].y > -2)
                                mov = mov;
                            else
                                mov = true;
                            element.position = pos;
                        });
                        isMoving = mov;
                    }
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

                // The "big" bubble
                //drawCircle(bigBubble.radius, bigBubble.position,  biggrd); 

                // Draw the small bubbles
                bubbleList.forEach(function(element) {
                    drawCircle(element.radius, element.position, smallgrd);
                });
            }
            function onBlur(e, self) {
                isTypingMeta = false;   
            }
            function onFocus(e, self) {
                isTypingMeta = true;
            }
            function destroyAllHumans() {
                if (input.title != null) {
                    input.title.blur();
                    input.title.destroy();
                    input.title = null;
                }
                if (input.author != null) {
                    input.author.blur();
                    input.author.destroy();
                    input.author = null;
                }
            }
            function drawTransition(cb) {
                drawCircle(transition.radius, transition.position, smallgrd);
                if (escapeBubble) {
                    if (transition.radius > 75)
                        transition.radius -= 10;
                    else {
                        isIdle = true;
                        isMoving = true;
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
                
                // The "big" bubble
                //drawCircle(bigBubble.radius, bigBubble.position,  biggrd);

                // The "inner" bubble
                drawTransition(function() {
                    if (activeBubble.title == "") {
                        if (input.title == null)
                            input.title = new CanvasInput({
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
                        input.title.render();
                    } 
                    if (activeBubble.author == "") {
                        if (input.author == null)
                            input.author = new CanvasInput({
                                canvas: canvas,
                                x: bigBubble.position.x,// - 220,
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
                        input.author.render();
                        var count = 0;
                        context.fillStyle = "rgb(0, 0, 0)";
                        context.font = "20px Verdana";
                    }
                    context.fillStyle = "rgb(0, 0, 0)";
                    context.font = "20px Verdana";
                    
                    context.fillText(activeBubble.title, bigBubble.position.x - (activeBubble.title.length / 2 * 16), bigBubble.position.y - bigBubble.radius / 1.5);
                    context.fillText("Author: ", bigBubble.position.x - 200, bigBubble.position.y - bigBubble.radius / 2 + 20);
                    context.fillText(activeBubble.author, bigBubble.position.x, bigBubble.position.y - bigBubble.radius / 2 + 20);
                    context.fillStyle = "rgba(255, 255, 45, 0.54)";
                    context.fillRect(bigBubble.position.x - bigBubble.radius + 75, bigBubble.position.y, bigBubble.radius * 2 - 150, bigBubble.radius - 150);
                });
            }
            function onSubmit(e, self) {
                if (self == input.title && self._value != "")
                    activeBubble.title = self._value;
                else if (self == input.author && self._value != "")
                    activeBubble.author = self._value;
                self.destroy(); // to destroy the input
                self = null;
            }
        </script>
</body>



<?php
include "includes/footer.php";
?>
