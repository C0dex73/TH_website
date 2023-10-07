<?php echo '
<!DOCTYPE html>
<html lang="fr">
<head>
<link rel="stylesheet" href="../css/blog.css">
<link rel="stylesheet" href="../css/style.css">
</head>
<body>';

//*script

//*Import functions
include("./functions.php");

//! configs are taken from the config.php file, see README.md for details
$config = include("./config.php");
define('CONFIG', $config);

$servername = CONFIG['SERVERNAME'];
$dbusername = CONFIG['USERNAME'];
$password = CONFIG['PASSWORD'];
$db = CONFIG['DB'];

$conn = new mysqli($servername, $dbusername, $password, $db);
define('CONN', $conn);
if (CONN->connect_error) { die("Connection failed: " . CONN->connect_error);}

$username = $token = '-1';
$token = secureGet('token', 'get');

$sql = 'SELECT `username` FrOM login WHERE `token`="' . $token . '"';
$result = CONN->query($sql);
if($result->num_rows > 0){
    $username = $result->fetch_row()[0];
}


$sql = 'SELECT `title`,`author` FROM blog';
$result = CONN->query($sql);
if($result->num_rows > 0){
    echo '<div class="bubbles">';
    // TODO : post button
    if($username != '-1'){
        echo   '<div class="bubble" id="post">
                <form class="invisible" method="post" action="../php/bubble.php">
                <h1>Poster un article</h1>
                <input type="hidden" id="token" name="token" value="' . $token . '"/>
                <input type="hidden" 
                </form>
                </div>';
    }
    //end todo
    while($row = $result->fetch_assoc()) {
        echo '<div class="bubble"><p><i> - ' . $row['author'] . '</i></p><h2>'. $row['title'] . '</h2></div>';
    }
    echo '</div>';
}else{
    echo '<p><i>Tout est vide, pour l\'instant...</i></p>';
}



echo '<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script type="text/javascript" src="../js/index.js"></script>
<script type="text/javascript" src="../js/blog.js"></script>
</body></html>'; ?>