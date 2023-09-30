function signUpSend(event) {
    document.getElementById("signupform").submit();
}

document.getElementById("signupbutton").addEventListener("click", signUpSend);

document.getElementById("formdiv").style.paddingTop = (window.innerHeight/2-document.getElementById("loginform").clientHeight/2).toString() + "px";