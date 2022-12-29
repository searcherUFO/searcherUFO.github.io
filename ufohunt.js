 var x = 200;
var y = 100;
var up = false;
var right = false;
var left = false;
var gravity = -1.1;
var horizontalSpeed = 0.1;

var canvas = document.querySelector("canvas");
var width=canvas.width;
var height=canvas.height;
var ctx = canvas.getContext("2d");


init();

function init() {
  document.onkeydown = handleKeyDown;
  document.onkeyup = handleKeyUp;

  animate();
}

function animate() {
  moveShip();
  drawScene();
  requestAnimationFrame(animate);
}
function drawScene(){
  ctx.clearRect(0,0,1600,800);
  drawSpaceship();
}
function moveShip() {
  if (left) {
    horizontalSpeed -= 0.1;
  }else if (right){
    horizontalSpeed += 0.1;
  }
  if (up) {
    gravity -=0.4;
  }
  gravity += 0.12;
  y += gravity;
  x += horizontalSpeed;
  x=Math.min(Math.max(x,0+20),width-80);
  y=Math.min(Math.max(y,0+80),height-90);
  if(y==height-90){gravity=0;}
}
function drawSpaceship () {
  ctx.save();
  ctx.translate(x, y);
  ctx.beginPath();
  ctx.moveTo(0, 0);
  ctx.lineTo(0, -40);
  ctx.lineTo(30,-80);
  ctx.lineTo(60,-40);
  ctx.lineTo(60,0);
  ctx.lineTo(80,30);
  ctx.lineTo(80,90);
  ctx.lineTo(60,50);
  ctx.lineTo(0,50);
  ctx.lineTo(-20,90);
  ctx.lineTo(-20,30);
  ctx.lineTo(0,0);
  ctx.strokeStyle = 'blue';
  ctx.stroke();
  ctx.closePath();
  ctx.restore();
}
function handleKeyDown (evt) {
  evt = evt || window.event;
  switch (evt.keyCode) {
    case 37:
      left = true;
      break;
    case 38:
      up = true;
      break;
    case 39:
      right = true;
      break;
  }
}
function handleKeyUp(evt) {
  evt = evt || window.event;
  switch (evt.keyCode) {
    case 37:
      left = false;
      break;
    case 38:
      up = false;
      break;
    case 39:
      right = false;
      break;
  }
}
