<?php

//* Import functions
include("./php/functions.php");

//! configs are taken from the config.php file, see README.md for details
$config = include("./php/config.php");
define('CONFIG', $config);


//* DATA BASE LOGIN (TEMP) + VARS DEF

$servername = CONFIG['SERVERNAME'];
$dbusername = CONFIG['USERNAME'];
$password = CONFIG['PASSWORD'];
$db = CONFIG['DB'];
$version = CONFIG['VERSION'];

$conn = new mysqli($servername, $dbusername, $password, $db);
define('CONN', $conn);
if (CONN->connect_error) { die("Connection failed: " . CONN->connect_error);}

$M_Acode = $Acode = $Cemail = $correct = $pass = $email = $state = $checkpassword = $password = $username = "-1";






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
}

switch ($state){
    case '1':
        if(signup($username, $password, $checkpassword, $email, $Acode)){
            $datetime = new DateTime();
            $datetime->modify('+1 day');
            $exVcode = $datetime->format('Y-m-d H:i:s');
            $vCode = unique_id($l = 4);
            $token = unique_id($l = 16);
            $sql = "INSERT INTO `login` (`username`, `password`, `token`, `email`, `Vcode`, `VcodeEx`) VALUES ('". $username ."', '" . $password . "', '". $token . "', '" . $email . "', '" . $vCode . "', '". $exVcode ."') ";
            $result = $conn->query($sql);
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
        include("./pages/blog.html");
        break;
    default :
        include("./pages/login.html");
        break;
}

//& database connection closed
$conn->close();
?>