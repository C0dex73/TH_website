<?php

//* Import functions
include("./php/functions.php");
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
require 'php/mail.php';

//! configs are taken from the config.php file, see README.md for details
$config = include("./php/config.php");
define('CONFIG', $config);


//* DATA BASE LOGIN (TEMP) + VARS DEF

$servername = CONFIG['SERVERNAME'];
$dbusername = CONFIG['USERNAME'];
$password = CONFIG['PASSWORD'];
$db = CONFIG['DB'];
$version = CONFIG['CSSJSVERSION'];

$conn = new mysqli($servername, $dbusername, $password, $db);
define('CONN', $conn);
if (CONN->connect_error) { die("Connection failed: " . CONN->connect_error);}

$content = $title = $id = $token = $M_Acode = $Acode = $Cemail = $correct = $pass = $email = $state = $checkpassword = $password = $username = "-1";






//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ MAIN SCRIPT ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ \\





//* Process data before rendering

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $state = secureGet('state');
    $pass = secureGet('pass');
    if($pass == '1'){
        $Acode = secureGet('Acode');
        $vCode = secureGet('vCode');
        $username = secureGet('username');
        $password = secureGet('password');
        $email = secureGet('email');
        $checkpassword = secureGet('checkpassword');
    }
    $content = secureGet('contentvalue');
    $title = secureGet('title');
    $id = secureGet('id');
    $token = secureGet('token');
}

switch ($state){
    case '1':
        if(signup($username, $password, $checkpassword, $email, $Acode)){
            $datetime = new DateTime();
            $datetime->modify('+1 day');
            $exVcode = $datetime->format('Y-m-d H:i:s');
            $vCode = unique_id($l = 4);
            do{
                $token = unique_id($l = 16);
                $sql = 'SELECT `token` FROM `login` WHERE `token`="' . $token . '"';
                $result = CONN->query($sql);
            }while ($result->num_rows > 0);
            $sql = "INSERT INTO `login` (`username`, `password`, `token`, `email`, `Vcode`, `VcodeEx`) VALUES ('". $username ."', '" . $password . "', '". $token . "', '" . $email . "', '" . $vCode . "', '". $exVcode ."') ";
            $result = CONN->query($sql);
            sendMail($email, $vCode, $username);
            $state = '0';
        }
        $M_Acode = Vacode($Acode);
        if($M_Acode != ""){
            $Acode = "";
        }
    break;
    case '-2':
        $correct = login($username, $password);
        switch ($correct){
            case '-2' :
                $correct = "Vérifiez votre E-mail avant de vous connecter";
                $state = '2';
            break;
            case '-1';
                $correct = "";
                $state = '2';
            break;
            case '0' :
                $correct = "Mauvais mot de passe";
                $state = '2';
            break;
            case '1' :
                $state = "";
                $token = getToken($username);
            break;
        }
        break;
    case '0' :
        if($email != "-1"){
            if(emailVerify($email) == "Cette e-mail est déjà prise"){
                $sql = 'SELECT `Vcode`, `VcodeEx` FROM login WHERE `email`="'. $email .'"';
                $result = CONN->query($sql);
                $row = $result->fetch_row();
                $code = $row[0];
                $exCode = new DateTime($row[1]);
                $now = new DateTime();
                if($code == "-1"){
                    $Cemail = "E-mail déjà vérifiée";
                }else{
                    if($code == $vCode){
                        if($now < $exCode){
                            $sql = 'UPDATE login SET `Vcode`="-1" WHERE `email`="' . $email .'"';
                            $result = CONN->query($sql);
                            $state="2";
                            $Cemail = "OK";
                            $username = "-1";
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
            $result = CONN->query($sql);
            if($result->num_rows > 0){
                if($result->fetch_row()[0] != "-1"){
                    $sql = 'DELETE FROM login WHERE `email`="' . $email . '"';
                    $result = CONN->query($sql);
                }
            }
        }
    break;
    case '4':
        $sql = 'DELETE FROM `login` WHERE `email`="'. $email . '"';
        CONN->query($sql);
        $state = "2";
    break;
    case '-4':
        $sql = 'SELECT `username` FROM `login` WHERE `token`="' . $token . '"';
        $result = CONN->query($sql);
        if($result->num_rows > 0){
            $state = "4";
        }else{
            $state = "2";
            $correct = "Vous devez vous connecter pour poster !";
        }
    break;
    case '-5':
        $filesPath = array();
        $content = $content . "<br/>";
        if($_FILES['upload']['name'][0] != ""){
            $files = array_filter($_FILES['upload']['name']);
            $total = count($_FILES['upload']['name']);
            for( $i=0 ; $i < $total ; $i++ ) {
                $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                if ($tmpFilePath != ""){
                    $newFilePath = './medias/uploaded/' . $_FILES['upload']['name'][$i];
                    $ext = pathinfo($newFilePath, PATHINFO_EXTENSION);
                    while(file_exists($newFilePath)){
                        $dotPos = strlen($newFilePath) - strlen($ext) - 1;
                        $newFilePath = substr($newFilePath,0, $dotPos) . '_duplicate.' . $ext;
                    }
                    array_push($filesPath, $newFilePath);
                    move_uploaded_file($tmpFilePath, $newFilePath);
                    switch(strtolower($ext)){
                        case 'jpg' :
                        case 'png' :
                        case 'jpeg' :
                        case 'svg' :
                        case 'gif' :
                            $content = $content . '<img src="' . $newFilePath . '" style="width:100%;" alt="Image nommée '. $_FILES['upload']['name'][$i] . '"/>';
                            break;
                        case 'mov' :
                        case 'mp4' :
                        case 'avi' :
                            if(strtolower($ext) === 'mov') { $ext = 'mp4'; }
                            $content = $content . '<video controls><source src="' . $newFilePath . '" type="video/' . $ext . '"/>Vidéo nommée '. $_FILES['upload']['name'][$i] . '</video>';
                            break;
                        default :
                            $content = $content . '<a href="'. $newFilePath . '" download="'. $_FILES['upload']['name'][$i] . '"><button type="button" class="download-button"><img class="icon" height="30" width="30" src="./medias/download.png" alt="Icône télécharger"/><p>Télécharger '. $_FILES['upload']['name'][$i] . '</p></button></a>';
                    }
                }
            }
        }
        $username = getUsername($token);
        if ($title == ""){ $title = "Sans Titre"; }
        $sql = 'INSERT INTO `blog` (`id`, `author`, `title`, `content`, `files`, `published`) VALUES (NULL, \''. $username . '\', \''. $title . '\', \''. $content . '\', \'['. implode(",", $filesPath) .']\', current_timestamp()) ';
        $result = CONN->query($sql);
    break;
}

//* Render the page
switch ($state){
    case '0' : 
        include("./html/email.html");
    break;
    case '1' :
        include("./html/singup.html");
    break;
    case '2' :
        include("./html/login.html");
    break;
    case '3' :
        include("./php/bubble.php");
    break;
    case '4' :
        include("./html/post.html");
    break;
    case '400' :
        include("./html/indev.html");
    break;
    default :
        include("./php/blog.php");
    break;
}

//& database connection closed
CONN->close();
