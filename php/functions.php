<?php





// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ FUNCTIONS ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ \\







function secureGet($key, $method = 'post'){
    switch($method){
        case 'post' :
            if(isset($_POST[$key])){
                return $_POST[$key];
            }
            break;
        case 'get' :
            if(isset($_GET[$key])){
                return $_GET[$key];
            }
            break;
    }
    return "";
}
 
function toUser($post){
    if($post == '-1'){
        return "";
    }
    return $post;
}

//TODO : handle -1 username
function usernameVerify($username, $signup){
    if($username == ""){
        return "Nom d'utilisateur invalide";
    }
    if($username == "-1"){
        return "";
    }
    $sql = 'SELECT `username` FROM `login` WHERE `username`="' . $username . '"';
    $result = CONN->query($sql);
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
    $sql = 'SELECT * FROM `login` WHERE `email`="'. $email . '"';
    $result = CONN->query($sql);
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
    if(usernameVerify(toUser($_username), false) != ""){
        return "-1";
    }
    $sql = 'SELECT `Vcode` FROM `login` WHERE `password`="'. $_password . '" AND `username`="'. $_username . '"';
    $result = CONN->query($sql);
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
    return "";
}

function sendMail($_email, $_Vcode, $_username){
    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Host = 'smtp.hostinger.fr';
    $mail->Port = 587;
    $mail->SMTPAuth = true;
    $mail->Username = 'admins@jdath.fr';
    $mail->Password = '@dm1ns4Jd@.fr';
    $mail->setFrom('admins@jdath.fr', 'TH Blog admin team');
    $mail->addAddress($_email, $_username);
    $mail->Subject = 'Code de vérification';
    $mail->isHTML();
    $mail->CharSet = 'UTF-8';
    $mail->Body = buildMail($_Vcode, $_username);
    if (!$mail->send()) {
        echo '-1';
    } else {
        echo '1';
    }
}

function unique_id($l = 8) {
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

function getToken($_username){
    if(toUser($_username) == ""){
        return "undefined";
    }
    $sql = 'SELECT `token` from login WHERE `username` = "' . $_username . '"';
    $result = CONN->query($sql);
    return $result->fetch_row()[0];
}

?>