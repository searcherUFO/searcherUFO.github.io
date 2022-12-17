let result = document.getElementById("result");
let buttons = Array.from(document.getElementsByClassName("btn"));

buttons.map((button) => {
  button.addEventListener("click", (e) => {
    if (
      result.innerText === "0" &&
      e.target.innerText != "/" &&
      e.target.innerText != "*"
    ) {
      result.innerText = "";
    }
    if (e.target.innerText === "AC") {
      result.innerText = "";
    } else if (e.target.innerText === "=") {
      try {
        result.innerText = eval(result.innerText);
      } catch {
        result.innerText = "ERROR.";
      }
    } else {
      result.innerText += e.target.innerText;
    }
  });
});
