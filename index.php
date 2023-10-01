<?php

//! configs are taken from the config.php file, see README.md for details
$config = include("./config.php");
define('CONFIG', $config);


//* DATA BASE LOGIN (TEMP) + VARS DEF

$servername = CONFIG['SERVERNAME'];
$username = CONFIG['USERNAME'];
$password = CONFIG['PASSWORD'];
$db = CONFIG['DB'];

$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error);}

$Acode = $Cemail = $correct = $pass = $email = $state = $checkpassword = $password = $username = "-1";





// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ FUNCTIONS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ \\







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
    if(toUser($email) == ""){
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
    $sql = 'SELECT `Vcode` FROM `login` WHERE `password`="'. $_password . '" AND `username`="'. $_username . '"';
    $result = $conn->query($sql);
    if($result->num_rows == 0){
        return "0";
    }
    if($result->fetch_row()[0] == -1){
        return "1";
    }
    return "-2";
}

function signup($_username, $_password, $_checkpassword, $_email, $_Acode){
    if(usernameVerify($_username, true) != ""
    || passwordMatch($_password, $_checkpassword) != ""
    || emailVerify($_email) != ""
    || $_username == "-1"
    || $_password == "-1"
    || $_email == "-1"
    || $_checkpassword == "-1"
    || $_Acode != CONFIG['ACODE']){
        return false;
    }
    return true;
}

function Vacode($_Acode){
    if($_Acode != '-1'){
        if($_Acode == ""){
            return "Code d'inscription obligatoire";
        }
        if($_Acode != CONFIG['ACODE']){
            return "Code d'inscription incorrect";
        }
    }
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

//TODO : send mail (waiting for server)
function sendMail($_email, $_Vcode){

}

function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}






//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MAIN SCRIPT ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ \\






//* Process data before rendering

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $state = secureSet('state');
    $pass = secureSet('pass');
    if($pass == '1'){
        $vCode = secureSet('vCode');
        $username = secureSet('username');
        $password = secureSet('password');
        $email = secureSet('email');
        $checkpassword = secureSet('checkpassword');
    }
}

switch ($state){
    case '1':
        if(signup($username, $password, $checkpassword, $email, $Acode)){
            $datetime = new DateTime();
            $datetime->modify('+1 day');
            $exVcode = $datetime->format('Y-m-d H:i:s');
            $vCode = uniqid($l = 4);
            $token = uniqid($l = 16);
            $sql = "INSERT INTO `login` (`username`, `password`, `token`, `email`, `Vcode`, `VcodeEx`) VALUES ('". $username ."', '" . $password . "', '". $token . "', '" . $email . "', '" . $vCode . "', '". $exVcode ."') ";
            $result = $conn->query($sql);
            sendMail($email, $vCode);
            $state = '0';
        }
        break;
    case '-2':
        $correct = login($username, $password);
        switch ($correct){
            case '-2' :
                $correct = "Vérifiez votre E-mail avant de vous connecter";
                break;
            case '0' :
                $correct = "Mauvais mot de passe";
                break;
            case '1' :
                $state = "2";
                break;
        }
        break;
    case '0' :
        if($email != "-1"){
            if(emailVerify($email) == "Cette e-mail est déjà prise"){
                $sql = 'SELECT `Vcode`, `VcodeEx` FROM login WHERE `email`="'. $email .'"';
                $result = $conn->query($sql);
                $row = $result->fetch_row();
                $code = $row[0];
                $exCode = new DateTime($row[1]);
                $now = new DateTime();
                if($code == "-1"){
                    $correct = "E-mail déjà vérifiée";
                }else{
                    if($code == $vCode){
                        if($now < $exCode){
                            $sql = 'UPDATE login SET `Vcode`="-1" WHERE `email`="' . $email .'"';
                            $result = $conn->query($sql);
                            $state="";
                            $Cemail = "OK";
                        }else{
                            $correct = "Code de vérification expiré, veuillez recréer un compte";
                            $sql = 'DELETE FROM login WHERE `email`="'. $email .'"';
                        }
                    }else{
                        $correct = "Mauvais code de vérification";
                    }
                }
            }else{
                $Cemail = "E-mail inconnue";
            }
        }
        break;
    case '-3' :
        $username = "-1";
        if(toUser($email) != ""){
            $sql = 'SELECT `Vcode` FROM login WHERE `email`="'. $email .'"';
            $result = $conn->query($sql);
            if($result->num_rows > 0){
                if($result->fetch_row()[0] != "-1"){
                    $sql = 'DELETE FROM login WHERE `email`="' . $email . '"';
                    $result = $conn->query($sql);
                }
            }
        }
        break;
}

//* Render the page
switch ($state){
    case '0' : 
        include("./pages/email.html");
        break;
    case '1' :
        include("./pages/singup.html");
        break;
    case '2' :
        if(isMobile()){
            //TODO : build mobilepage
            include("./pages/mobilepage.html");
        }else{
            //TODO : build mainpage
            include("./pages/mainpage.html");
        }
        break;
    default :
        include("./pages/login.html");
        break;
}

//& database connection closed
$conn->close();
?>