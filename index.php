<?php

//temp DB login
$servername = "localhost";
$username = "codex";
$password = "8UMZw(ZUyedlsURI";
$db = "th_internal";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

function secureSet($key){
    if(isset($_POST[$key])){
        return $_POST[$key];
    }
    return "";
}
 
function toUser($post){
    if($post == '-1'){
        return "";
    }
    return $post;
}

function usernameVerify($username, $signup){
    if($username == ""){
        return "Nom d'utilisateur invalide";
    }
    if($username == "-1"){
        return "";
    }
    global $conn;
    $sql = 'SELECT `username` FROM `login` WHERE `username`="' . $username . '"';
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        return $signup ? "Nom d'utilisateur déjà pris" : "";
    }
    return $signup ? "" : "Nom d'utilisateur inconnu";
}

function emailVerify($email){
    if($email == '-1'){
        return "";
    }
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return "format d'e-mail invalide";
    }
    return "";
}

function login($_username, $_password){
    if(usernameVerify($_username, false) != ""){
        return "-1";
    }
    global $conn;
    $sql = 'SELECT * FROM `login` WHERE `password`="'. $_password . '" AND `username`="'. $_username . '"';
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        return "1";
    }
    return "0";
}

$correct = $pass = $email = $state = $password = $username = "-1";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $state = secureSet('state');
    $pass = secureSet('pass');
    if($pass == '1'){
        $username = secureSet('username');
        $password = secureSet('password');
        $email = secureSet('email');
    }
}

if($state == '-2'){
    $correct = login($username, $password);
    if($correct == "1"){
        $state = '2';
    }else if($correct == "0"){
        $correct = "Mauvais mot de passe";
    }
}

switch ($state){
    case '0' : 
        include("./pages/mainpage.html");
        break;
    case '1' :
        include("./pages/singup.html");
        break;
    case '2' :
        include("./pages/mainpage.html");
        break;
    default :
        include("./pages/login.html");
        break;
}
?>