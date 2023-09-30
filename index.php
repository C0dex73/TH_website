<?php

$log = array();
function console($message){
    global $log;
    $log = array_merge($log, array(strval($message)));
}

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
    global $conn;
    $sql = 'SELECT * FROM `login` WHERE `email`="'. $email . '"';
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        return "Cette e-mail est déjà prise";
    }
    return "";
}

function passwordMatch($password1, $password2){
    if($password1 == "" || $password2 == ""){
        return "Mot de passe obligatoire";
    }
    if($password1 == $password2 || $password2 == "-1" || $password1 == "-1"){
        return "";
    }
    return "Les mots de passe ne correspondent pas";
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

function signup($_username, $_password, $_checkpassword, $_email){
    //console(usernameVerify($_username, true));
    if(usernameVerify($_username, true) != ""
    || passwordMatch($_password, $_checkpassword) != ""
    || emailVerify($_email) != ""
    || $_username == "-1"
    || $_password == "-1"
    || $_email == "-1"
    || $_checkpassword == "-1"){
        return false;
    }
    return true;
}

$correct = $pass = $email = $state = $checkpassword = $password = $username = "-1";
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $state = secureSet('state');
    $pass = secureSet('pass');
    if($pass == '1'){
        $username = secureSet('username');
        $password = secureSet('password');
        $email = secureSet('email');
        $checkpassword = secureSet('checkpassword');
    }
}

if($state == '1' && signup($username, $password, $checkpassword, $email)){
    $state = '0';
}

if($state == '-2'){
    $correct = login($username, $password);
    if($correct == "1"){
        $state = '2';
    }else if($correct == "0"){
        $correct = "Mauvais mot de passe";
    }
}

$log = implode("\n", $log);

//build page
switch ($state){
    case '0' : 
        include("./pages/email.html");
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