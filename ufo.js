var character = document.getElementById("character");
document.addEventListener("click",jump);
function jump(){
    character.classList.add("animate");
    setTimeout(removeJump,300); //300ms = length of animation
};
function removeJump(){
    character.classList.remove("animate");
}
if(character.classList == "animate"){return;}
#block{
    width: 20px;
    height: 20px;
    background-color: blue;
    position: relative;
    top: 130px; //game height - character height - block height (200 - 50 - 20)
    left: 480px; //game width - block width (500 - 20)
    animation: block 1s infinite linear;
}
@keyframes block{
    0%{left: 500px} 
    100%{left: -20px}
}
var block = document.getElementById("block");
function checkDead(){
    let characterTop = parseInt(window.getComputedStyle(character).getPropertyValue("top"));
    let blockLeft = parseInt(window.getComputedStyle(block).getPropertyValue("left"));
    if(blockLeft<20 && blockLeft>-20 && characterTop>=130){
        alert("Game over");
    }
}

setInterval(checkDead, 10);
