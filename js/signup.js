document.getElementById("formdiv").style.paddingTop = (window.innerHeight/2-document.getElementById("signup").clientHeight/2).toString() + "px";

function signUpSend(event) {
    document.getElementById("loginform").submit();
}

document.getElementById("loginbutton").addEventListener("click", signUpSend);