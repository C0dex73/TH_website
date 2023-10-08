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

$date = $author = $files = $content = $title = $username = $token = $bubbleID = '-1';

$token = secureGet('token');
$bubbleId = secureGet('id');

// TODO : edit button
// TODO : delete button
// TODO : display bubble
echo '<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/blog.js"></script>
</body>
</html>
'; ?>