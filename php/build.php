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
$username = CONFIG['USERNAME'];
$password = CONFIG['PASSWORD'];
$db = CONFIG['DB'];

$conn = new mysqli($servername, $username, $password, $db);
define('CONN', $conn);
if (CONN->connect_error) { die("Connection failed: " . CONN->connect_error);}

$token = $_GET['token'];

$sql = 'SELECT `username` FrOM login WHERE `token`="' . $token . '"';
$result = CONN->query($sql);
if($result->num_rows > 0){
    $username = $result->fetch_row()[0];
    $sql = 'SELECT `title`,`author` FROM blog';
    $result = CONN->query($sql);
    if($result->num_rows > 0){
        echo '<div class="bubbles">';
        while($row = $result->fetch_assoc()) {
            echo '<div class="bubble"><p><i> - ' . $row['author'] . '</i></p><h2>'. $row['title'] . '</h2></div>';
        }
        echo '</div>';
    }else{
        echo '<p><i>Tout est vide, pour l\'instant...</i></p>';
    }
}else{
    echo '<p class="verify">Token invalide, veuillez vous reconnecter</p>';
}



echo '<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script type="text/javascript" src="../js/index.js"></script>
<script type="text/javascript" src="../js/blog.js"></script>
</body></html>'; ?>