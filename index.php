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
    <div id="indeximg"></div>
</div>

<script type="text/javascript">
            var requestAnimationFrame = window.requestAnimationFrame || 
                window.mozRequestAnimationFrame || 
                window.webkitRequestAnimationFrame || 
                window.msRequestAnimationFrame;
            
            var isIdle = true;
            var transition;
            var activeBubble;
            var bubbleList = [];
            var canvas = loadCanvas("content");
            var context = canvas.getContext("2d");
            var bigBubble = {radius: 300, position:{x: 325, y: 325}, q: e, D: 0.005};
            var e0 = 8.85418781762E-12;
            var e = 1.602176565E-19;

            bubbleList.push({radius: 75, position:{x: 200, y: 200}, q: e * 10E+14});
            bubbleList.push({radius: 75, position:{x: 375, y: 350}, q: e * 10E+14});
            bubbleList.push({radius: 75, position:{x: 475, y: 200}, q: e * 10E+14});
            bubbleList.push({radius: 75, position:{x: 475, y: 475}, q: e * 10E+14});
            bubbleList.push({radius: 75, position:{x: 200, y: 475}, q: e * 10E+14});
            setInterval(physics, 33, bubbleList);

            function loadCanvas(id) {
                var canvas = document.createElement('canvas');
                var div = document.getElementById(id); 

                canvas.id     = "CursorLayer";
                canvas.width  = 650;
                canvas.height = 650;
                canvas.style.zIndex   = 8;
                canvas.style.position = "absolute";
                canvas.style.border   = "1px solid";
                canvas.addEventListener("mousedown", doMouseDown, false);
                div.appendChild(canvas);

                return canvas;
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
            function physics(bubbleList) {
                if (isIdle) {
                    // Physics, only when the user is idle
                    var movementVectors = [];
                    bubbleList.forEach(function(element, index, array) {
                        var vector = {x: 0, y: 0};
                        array.forEach(function(other, otherindex) {
                            if (index != otherindex) {
                                if (element.position.x != other.position.x)
                                    vector.x += (element.q * other.q) / (4 * Math.PI * e0 * (element.position.x - other.position.x));
                                if (element.position.y != other.position.y)
                                    vector.y += (element.q * other.q) / (4 * Math.PI * e0 * (element.position.y - other.position.y));
                            }
                        });
                        vector.x += bigBubble.D * bubbleList.length * (bigBubble.position.x - element.position.x);
                        vector.y += bigBubble.D * bubbleList.length * (bigBubble.position.y - element.position.y);
                        vector.x = vector.x > 25 ? 25 : vector.x < -25 ? -25 : vector.x;
                        vector.y = vector.y > 25 ? 25 : vector.y < -25 ? -25 : vector.y;
                        movementVectors[index] = vector;
                    });
                    bubbleList.forEach(function(element, index) {
                        element.position.x += movementVectors[index].x;
                        element.position.y += movementVectors[index].y;

                        if (!circleIsInside(bigBubble, element)) { // Revert the vector to prevent leaving
                            element.position.x -= movementVectors[index].x * 1.5;
                            element.position.y -= movementVectors[index].y * 1.5;
                        }
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

                // The two types of gradients
                var biggrd = context.createLinearGradient(0, 0, 325, 325);
                biggrd.addColorStop(0, "rgba(0, 128, 0, 0.3)");
                biggrd.addColorStop(1, "rgba(0, 128, 0, 0.9)");
                var smallgrd = context.createLinearGradient(0, 0, 75, 75);
                smallgrd.addColorStop(0, "rgba(255, 185, 0, 0.3");
                smallgrd.addColorStop(1, "rgba(255, 185, 0, 0.9");

                // The "big" bubble
                drawCircle(bigBubble.radius, bigBubble.position,  biggrd); 

                // Draw the small bubbles
                bubbleList.forEach(function(element) {
                    drawCircle(element.radius, element.position, smallgrd);
                });
            }
            function drawInnerBubble() {
                // Clear the Canvas
                context.clearRect(0, 0, canvas.width, canvas.height); 

                // The two types of gradients
                var biggrd = context.createLinearGradient(0, 0, 325, 325);
                biggrd.addColorStop(0, "rgba(0, 128, 0, 0.3)");
                biggrd.addColorStop(1, "rgba(0, 128, 0, 0.9)");
                var smallgrd = context.createLinearGradient(0, 0, 200, 200);
                smallgrd.addColorStop(0, "rgba(255, 185, 0, 0.3");
                smallgrd.addColorStop(1, "rgba(255, 185, 0, 0.9");

                // The "big" bubble
                drawCircle(bigBubble.radius, bigBubble.position,  biggrd);

                // The "inner" bubble
                drawCircle(transition.radius, transition.position, smallgrd);
                if (transition.position.x != bigBubble.position.x)
                    transition.position.x = Math.abs(Math.abs(bigBubble.position.x) - Math.abs(transition.position.x)) < 2 ? bigBubble.position.x : 
                        transition.position.x + (bigBubble.position.x - activeBubble.position.x) / 15;
                if (transition.position.y != bigBubble.position.y)
                    transition.position.y = Math.abs(Math.abs(bigBubble.position.y) - Math.abs(transition.position.y)) < 2 ? bigBubble.position.y : 
                        transition.position.y + (bigBubble.position.y - activeBubble.position.y) / 15;
                if (transition.position.x == bigBubble.position.x && transition.position.y == bigBubble.position.y)
                    if (transition.radius < bigBubble.radius - 25)
                        transition.radius += 5;
            }
        </script>
</body>



<?php
include "includes/footer.php";
?>
