<?php

$servername = "localhost";
$username = "codex";
$password = "8UMZw(ZUyedlsURI";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function secureSet($key){
    if(isset($_POST[$key])){
        return $_POST[$key];
    }
    return "";
}

/*promise = 0 : "OK" / "" ;
 *          1 : "OK" / "Utilisateur inconnu" / "" ;
 *          2 : "OK" / "Mot de passe incorrect" / "";
 */
function login($_username, $_password, $promise = 0){

}

$signup = $password = $username = "";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = secureSet('username');
    $password = secureSet('password');
    $signup = secureSet('signup');
}

if($signup == '1'){
    include("./pages/singup.html");
}else if(login($username, $password) == "OK"){
    include("./pages/mainpage.html");
}else{
    include("./pages/login.html");
}

?>